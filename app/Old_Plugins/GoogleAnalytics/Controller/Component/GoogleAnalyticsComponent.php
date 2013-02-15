<?php
/**
 * Google Analytics API Class
 * 
 *  
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * Author: Doug Tan 
 * Website: http://www.askaboutphp.com/
 * Version: 0.2
 */
 
class GoogleAnalyticsComponent extends Object {

  private $authCode;
  private $profileId;
  
  private $endDate;
  private $startDate;

  public function startup()
  {
    $this->endDate = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));
    $this->startDate = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 31, date("Y")));
  }

  public function shutdown()
  {
  }

  public function beforeRender()
  {
  }

  public function initialize()
  {
  }

  public function beforeRedirect()
  {
  }
  
   /**
   * Sets Profile ID
   *
   * @param string $id (format: 'ga:1234')
   */
   public function setProfile($id) {
      $id = 'ga:' . $id;
      //look for a match for the pattern ga:XXXXXXXX, of up to 10 digits 
      if (!preg_match('/^ga:\d{1,10}/',$id)) {
         throw new Exception('Invalid Profile ID set.');
      }
      $this->profileId = $id;

      return true;
   }
  
  /**
    * Sets the date range
    * 
    * @param string $startDate (YYYY-MM-DD)
    * @param string $endDate   (YYYY-MM-DD)
    */
  public function setDateRange($startDate, $endDate) {
    //validate the dates
    if (!preg_match('/\d{4}-\d{2}-\d{2}/', $startDate)) {
      throw new Exception('Format for start date is wrong, expecting YYYY-MM-DD format');
    }
    if (!preg_match('/\d{4}-\d{2}-\d{2}/', $endDate)) {
      throw new Exception('Format for end date is wrong, expecting YYYY-MM-DD format');
    }
    if (strtotime($startDate)>strtotime($endDate)) {
      throw new Exception('Invalid Date Range. Start Date is greated than End Date');
    }
    $this->startDate = $startDate;
    $this->endDate = $endDate;
    return TRUE;
  }
  
   /**
   * Retrieve the report according to the properties set in $properties
   *
   * @param array $properties
   * @return array
   */
  public function getReport($properties = array()) {
    if (!count($properties)) {
      die ('getReport requires valid parameter to be passed');
      return FALSE;
    }
    
    //arrange the properties in key-value pairing
    foreach($properties as $key => $value){
            $params[] = $key.'='.$value;
        }
    //compose the apiURL string
        $apiUrl = 'https://www.google.com/analytics/feeds/data?ids='.$this->profileId.'&start-date='.$this->startDate.'&end-date='.$this->endDate.'&'.implode('&', $params);

    //call the API
    $xml = $this->_callAPI($apiUrl);
    
    //get the results
    if ($xml) {
      $dom = new DOMDocument();
      $dom->loadXML($xml);
      $entries = $dom->getElementsByTagName('entry');
      foreach ($entries as $entry){
        $dimensions = $entry->getElementsByTagName('dimension');
        $dims = '';
        foreach ($dimensions as $dimension) {
          $dims .= $dimension->getAttribute('value').'~~';
        }

        $metrics = $entry->getElementsByTagName('metric');
        foreach ($metrics as $metric) {
          $name = $metric->getAttribute('name');
          $mets[$name] = $metric->getAttribute('value');
        }
        
        $dims = trim($dims,'~~');
        $results[$dims] = $mets;
        
        $dims='';
        $mets='';
      }
    } else {
      throw new Exception('getReport() failed to get a valid XML from Google Analytics API service');
    }
    return $results;
  }
  
   /**
   * Retrieve the list of Website Profiles according to your GA account
   *
   * @return array
   */
  public function getWebsiteProfiles() {
  
    // make the call to the API
    $response = $this->_callAPI('https://www.google.com/analytics/feeds/accounts/default');
    
    //parse the response from the API using DOMDocument.
    if ($response) {
      $dom = new DOMDocument();
      $dom->loadXML($response);
      $entries = $dom->getElementsByTagName('entry');
      foreach($entries as $entry){
        $tmp['title'] = $entry->getElementsByTagName('title')->item(0)->nodeValue;
        $tmp['id'] = $entry->getElementsByTagName('id')->item(0)->nodeValue;
        foreach($entry->getElementsByTagName('property') as $property){
          if (strcmp($property->getAttribute('name'), 'ga:accountId') == 0){
            $tmp["accountId"] = $property->getAttribute('value');
          }    
          if (strcmp($property->getAttribute('name'), 'ga:accountName') == 0){
             $tmp["accountName"] = $property->getAttribute('value');
          }
          if (strcmp($property->getAttribute('name'), 'ga:profileId') == 0){
            $tmp["profileId"] = $property->getAttribute('value');
          }
          if (strcmp($property->getAttribute('name'), 'ga:webPropertyId') == 0){
            $tmp["webProfileId"] = $property->getAttribute('value');
          }
        }
        $profiles[] = $tmp;
      }
    } else {
      throw new Exception('getWebsiteProfiles() failed to get a valid XML from Google Analytics API service');
    }
    return $profiles;
  }
  
   /**
   * Make the API call to the $url with the $authCode specified
   *
   * @param url
   * @return result from _postTo
   */
   private function _callAPI($url) {
      return $this->_postTo($url,array(),array("Authorization: GoogleLogin auth=".$this->authCode));
   }
    
   /**
   * Authenticate the email and password with Google, and set the $authCode return by Google
   *
   * @param none
   * @return none
   */
   public function authenticate($params = array()) {  
      $postdata = array(
          'accountType' => 'GOOGLE',
          'Email' => $params['email'],
          'Passwd' => $params['password'],
          'service' => 'analytics',
          'source' => 'askaboutphp-v01'
      );

      $this->setProfile($params['profileId']);

      $response = $this->_postTo("https://www.google.com/accounts/ClientLogin", $postdata);
      //process the response;
      if ($response) {
         preg_match('/Auth=(.*)/', $response, $matches);
         if (isset($matches[1])) {
            $this->authCode = $matches[1];

            return true;
         }
      }

      return false;
  }
    
   /**
   * Performs the curl calls to the $url specified. 
   *
   * @param string $url
   * @param array $data - specify the data to be 'POST'ed to $url
   * @param array $header - specify any header information
   * @return $response from submission to $url
   */
   private function _postTo($url, $data=array(), $header=array()) {
    
    //check that the url is provided
    if (!isset($url)) {
      return FALSE;
    }

    //send the data by curl
    $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    if (count($data)>0) {
      //POST METHOD
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    } else {
      $header[] = "application/x-www-form-urlencoded";
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    
    $response = curl_exec($ch);
        $info = curl_getinfo($ch);
    
        curl_close($ch);
    
    //print_r($info);
    //print $response;
    if($info['http_code'] == 200) {
      return $response;
    } elseif ($info['http_code'] == 400) {
      throw new Exception('Bad request - '.$response);
    } elseif ($info['http_code'] == 401) {
      throw new Exception('Permission Denied - '.$response);
    } else {
      return FALSE;
    }
    
  }
}
    
  //   /**
  //    * Google Chart
  //    */
  //   $currentMonth = date('m');
    // build month array ending with current month
    // $months = array();
    // $monthNames = array();
    // for ($i=1;$i<13;$i++) {
    //   $insertMonth = $i+$currentMonth;
    //   if ($insertMonth > 12) {
    //     $insertMonth = $insertMonth - 12;
    //   }
    //   // pad keys out to two digits
    //   if ($insertMonth < 10) {
    //     $insertMonth = '0'.(string)$insertMonth;
    //   }
    //   $months[$insertMonth] = 0;
    //   $monthNames[] = date('M', strtotime('+'.$i.' month'));
    // }
    
    // foreach($chart AS $key=>$data)
    // {
    //   $months[$key] = $data['ga:visits'];
    // }
    
    // $scale = max($months) + 1000;
    // $months = implode(",",$months);
    // $monthNameString = implode('|', $monthNames);
    
    // $chart_image = 'http://chart.apis.google.com/chart?cht=bvs&chs=400x300&chd=t:'.$months.'&chxl=0:|'.$monthNameString.'&chds=0,'.$scale.'&chxt=x,y&chxs=0,000000,12,0,lt|1,000000,10,1,lt&chxr=1,0,'.$scale;