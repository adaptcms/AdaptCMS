<?php
/**
* @package LastFm
* @version 1.0
* @author Koa Metter
* @link http://iamkoa.net
* @license http://www.opensource.org/licenses/bsd-license.php
* Modified to fit into Cake Helper, some adjustments to code
*/

class LastFMHelper extends AppHelper
{
	public $name = 'LastFM';
	/**
	* LastFm API URL
	* @var string
	*/
	public $base_url = "http://ws.audioscrobbler.com/2.0/";
	
	/**
	* LastFm auth URL
	* @var string
	*/
	public $auth_url = "http://www.last.fm/api/auth/";
	
	/**
	* LastFm API key
	* @var string
	*/
	public $key = null;
	
	/**
	* xml2array() attributes
	* @var int - 0 or 1
	*/
	public $xml_att = 1;

	public function __construct(View $view)
	{
		parent::__construct($view);
	}

	public function __call ($method, $args)
	{
		$this->url = $this->buildCall($method, $args);
		$this->xml = $this->makeCall($this->url);
		return $this->xml2array($this->xml, $this->xml_att);
	}

	/**
	 * Gets the API key
	 *
	 * @param string $glue The character that precedes the API key
	 * @return string Formatted API key
	 */
	private function getKey ($glue = null)
	{
		if (is_null($this->key)) return false;
		return $glue."api_key=".$this->key;
	}
	
	/**
	 * Gets the API call
	 *
	 * @param string $method LastFm API method - append with "auth_" to require authentication
	 * @return string Formatted API method
	 */
	private function getMethod ($method)
	{
		$ex = explode("_",$method);
		if ($ex[0] == 'auth') {
			/* we've requested authentication */
			
			// build auth url
			$url = $this->auth_url.$this->getKey("?");
			
			// make call
			exit(print_r($this->makeCall($url)));
			$method = $ex[1].".".$ex[2];
		} else $method = str_replace('_','.',$method);
		
		$this->method = "?method=";
		$this->method .= $method;
		$this->method .= '&';
		return $this->method;
	}
	
	/**
	 * Gets the API arguments
	 *
	 * @param string $method LastFm API args
	 * @return string Formatted API args
	 * @author Charlie Page
	 * Adjusted to work with array, cleaner look
	 */
	private function getArgs ($args)
	{
		if (is_array($args)) {
			foreach($args[0] as $key => $arg) {
				$this->args .= "&".$key."=".urlencode($arg);
			}

			return $this->args;
		}
	}
	
	private function buildCall ($method, $args)
	{
		$this->call_url  = $this->base_url;
		$this->call_url .= $this->getMethod($method);
		$this->call_url .= $this->getArgs($args);
		$this->call_url .= $this->getKey("&");
		return $this->call_url;
	}
	
	private function makeCall ($url)
	{
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 8);
		$this->results = curl_exec($ch);
		curl_close($ch);
		return $this->results;
	}
	
	/** 
	 * Converts the given XML text to an array in the XML structure.
	 * 
	 * @author Binny V A
	 * @link http://www.bin-co.com/php/scripts/xml2array/
	 * @param xml $contents - The XML text
	 * @param int $get_attributes - 1 or 0 - If this is 1 the function
	 *				  will get the attributes as well as the tag values -
	 *				  this results in a different array structure in the
	 *				  return value.
	 * @return array The parsed XML in an array form. 
	 */
	function xml2array ($contents, $get_attributes)
	{
	    if(!$contents) return array(); 

	    if(!function_exists('xml_parser_create')) { 
	        //print "'xml_parser_create()' function not found!"; 
	        return array(); 
	    } 
	    //Get the XML parser of PHP - PHP must have this module for the parser to work 
	    $parser = xml_parser_create();
	    xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 ); 
	    xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 1 ); 
	    xml_parse_into_struct( $parser, $contents, $xml_values ); 
	    xml_parser_free( $parser ); 

	    if(!$xml_values) return;//Hmm... 

	    //Initializations
	    $xml_array = array(); 
	    $parents = array(); 
	    $opened_tags = array(); 
	    $arr = array(); 

	    $current = &$xml_array; 

	    //Go through the tags. 
	    foreach($xml_values as $data) { 
	        unset($attributes,$value);//Remove existing values, or there will be trouble 

	        //This command will extract these variables into the foreach scope 
	        // tag(string), type(string), level(int), attributes(array). 
	        extract($data);//We could use the array by itself, but this cooler. 

	        $result = ''; 
	        if($get_attributes) {//The second argument of the function decides this. 
	            $result = array(); 
	            if(isset($value)) $result['value'] = $value; 

	            //Set the attributes too. 
	            if(isset($attributes)) { 
	                foreach($attributes as $attr => $val) { 
	                    if($get_attributes == 1) $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr' 
	                    /**  :TODO: should we change the key name to '_attr'? Someone may use the tagname 'attr'. Same goes for 'value' too */ 
	                } 
	            } 
	        } elseif(isset($value)) { 
	            $result = $value; 
	        } 

	        //See tag status and do the needed. 
	        if($type == "open") {//The starting of the tag '<tag>' 
	            $parent[$level-1] = &$current; 

	            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag 
	                $current[$tag] = $result; 
	                $current = &$current[$tag]; 

	            } else { //There was another element with the same tag name 
	                if(isset($current[$tag][0])) { 
	                    array_push($current[$tag], $result); 
	                } else { 
	                    $current[$tag] = array($current[$tag],$result); 
	                } 
	                $last = count($current[$tag]) - 1; 
	                $current = &$current[$tag][$last]; 
	            } 

	        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />' 
	            //See if the key is already taken. 
	            if(!isset($current[$tag])) { //New Key 
	                $current[$tag] = $result; 

	            } else { //If taken, put all things inside a list(array) 
	                if((is_array($current[$tag]) and $get_attributes == 0)//If it is already an array... 
	                        or (isset($current[$tag][0]) and is_array($current[$tag][0]) and $get_attributes == 1)) { 
	                    array_push($current[$tag],$result); // ...push the new element into that array. 
	                } else { //If it is not an array... 
	                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value 
	                } 
	            } 

	        } elseif($type == 'close') { //End of tag '</tag>' 
	            $current = &$parent[$level-1]; 
	        } 
	    } 

	    return($xml_array); 
	}
}