<?php
App::uses('AppHelper', 'View/Helper');
/**
 * This is a CakePHP helper to add a widget their forms to locate addresses (and geolocate) on a google map;
 * this helper depends on JQuery
 * 
 * javascript copied from.. well.. "heavily inspired" by:
 * 
 * http://tech.cibul.net/geocode-with-google-maps-api-v3/
 * This helper encampsulates the funtionalities of that script 
 * (address autocomplete, geococding, drag'n'drop reverse geocoding)
 * in an easy to use widget that can be seamlessy integrated in your forms
 *
 * @author Stefano Manfredini
 * @version 0.1.0
 * 
 * requirements: PHP5 / CakePhp 2.0
 * 
 * @link           http://stefanomanfredini.info/2012/04/cakephp-2-0-address-finder-helper/
 * 
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * Usage:
 * 1] include the helper in your controller as usual;
 * 
 * 2] Optionally set your default options in bootstrap.php:
 * 
   $config['AddressFinder'] = array(
        //map settings
        'height' => '300px',
        'width' => '450px',
        'default' => array('lat' => '44.8378942', 'lon' => '11.6204396'),
        //form fields settings
        'modelName' => 'Place',
        'fields' => array('lat' => 'lat', 'lon' => 'lon', 'address' => 'address'),
        'latlonFieldsVisibility' => 'readonly', //'normal', 'readonly' or 'hidden' <- if hidden check your Security settings
        //rendering behaviour setting                
        'includeGoogleMapsScript' => true,
        'includeJQuery' => false,  //usually already included
        'renderFields' => false,  // render only the map and script, 
                                  // the form already has the required fields. If true, render the fields too
        'preventSubmit' => true    // no submit on empty lat/lon;
    );
  
   Configure::write('AddressFinder',$config['AddressFinder']);
 * 
 * 3] in your view / element, call render() method (or directly renderMap) with custom options as needed
 * $options = array(
 *      'modelName' => 'MyModel',
 *      'fields' => array('lat' => 'latitude', 'lon' => 'longitude', 'address' => 'gmaps_address'),
 *      'renderFields' => true 
 * );
 * echo $this->AddressFinder->render($options);
 * 
 * 
 * 
 */
class AddressFinderHelper extends AppHelper {
    public $helpers = array('Html','Form');

    public $includeGoogleMapsScript;
    
    public $includeJQuery;
    
    public $jQueryIncluded;
    
//    public $renderForm;
    
    public $renderFields;
    
    public $preventSubmit;
    
    protected $_before = true;
//    
//    public $modelName;
//    
//    public $fields;
    
    protected $_defaultOptions = array(
        //map settings
        'height' => '300px',
        'width' => '450px',
        'default' => array('lat' => '44.8378942', 'lon' => '11.6204396'),
        //form fields settings
        'modelName' => 'Place',
        'fields' => array('lat' => 'lat', 'lon' => 'lon', 'address' => 'address'),
        'latlonFieldsVisibility' => 'readonly', //'normal', 'readonly' or 'hidden' <- if hidden check your Security settings
        //rendering behaviour setting                
        'includeGoogleMapsScript' => true,
        'includeJQuery' => false,  
        'inline' => true,
        'renderFields' => false,
        //'renderForm' => false,        
        'preventSubmit' => true    
    );
    
    protected $_actualOptions = array();
    
    protected $output = '';
    
    /**
     * contructor, sets default options as overridden from Config 
     * @param View $View
     * @param type $settings 
     */
    public function __construct(View $View, $settings = array()) {
        parent::__construct($View, $settings);
        
        $AddressFinder = (array)Configure::read('AddressFinder');
        $this->_actualOptions = am($this->_defaultOptions, $AddressFinder);                      
    }
    
    /**
     * initialize values; overrides default options with those passed from $this->render() 
     * and sets some property needed in other methods
     * @param type $options 
     */
    protected function _initialize($options = array()) {
        
        $this->_actualOptions = am($this->_actualOptions, $options);  
        
        $options = $this->_actualOptions;
        
        if(!is_null($options['includeJQuery'])) {
            $this->includeJQuery = $options['includeJQuery'];
        }
        if(!is_null($options['includeGoogleMapsScript'])) {
            $this->includeGoogleMapsScript = $options['includeGoogleMapsScript'];
        }
//        if(!empty($options['renderForm'])) {
//            $this->renderForm = $options['renderForm'];
//        }
        if(!is_null($options['renderFields'])) {
            $this->renderFields = $options['renderFields'];
        }
        if(!is_null($options['preventSubmit'])) {
            $this->preventSubmit = $options['preventSubmit'];
        }
        
    }
    
    /**
     * main method of the helper. 
     * calls other methods to build the html/javascript output, then prints it.
     * @param type $options 
     */
    public function render($options = array()) {
        
        $this->_initialize($options);
        
        $this->_includeScripts();
        
//        if($this->renderForm) $this->renderForm ($options);
//        else        
        if ($this->renderFields) $this->renderFields($options);
        
        $this->renderMap ($options);
        
        if ($this->renderFields) $this->renderFields($options);

        echo $this->output;
    }


    /**
     * "Borrowed" from Mark's GoogleMapHelper
     * https://github.com/dereuromark/cakephp-google-map-v3-helper
     * @param type $options 
     *  -inline
     *  -includeJQuery
     */    
    protected function _includeScripts ($inline = null) {
            
            if(!is_null($inline)) $this->_actualOptions['inline'] = $inline;
        
            $res = '';
        
		if($this->includeJQuery && !$this->jQueryIncluded) {
			$res = $this->Html->script('http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', array('inline'=> $this->_actualOptions['inline']));
			
                        $this->jQueryIncluded = true;			
		}		              
                
                if($this->includeGoogleMapsScript) {
                    $res .= $this->Html->script('http://maps.google.com/maps/api/js?sensor=false', array('inline'=> $this->_actualOptions['inline']));
                    
                }            
              
            $this->output .= $res;    
            //return $res;
                
    }
    
    
    /**
     * builds the map and client side autocomplete and (reverse/)geocoding script; main part of the output.
     * @param type $options
     * @throws CakeException 
     */   
    public function renderMap($options = array() ) {
        
        $options = $this->_actualOptions;
                
        //App::import('Inflector');
        
        if(!empty($options['modelName'])) {
            $addressField   = $options['modelName']. Inflector::camelize($options['fields']['address']);
            $latField       = $options['modelName']. Inflector::camelize($options['fields']['lat']);
            $lonField       = $options['modelName']. Inflector::camelize($options['fields']['lon']);
        } else {
            throw new CakeException(__('AddressFinderHelper - Error in fields definition'));
            
        }
        
        
$output = '<div id="map_canvas" style="width:'. $options['width'] .'; height:'. $options['height']  .'"></div><br/>    
<script language="javascript">
var geocoder;
var map;
var marker;
    
function initialize(){
//MAP
  var latlng = new google.maps.LatLng('.$options['default']['lat'].','. $options['default']['lon'].');
  var options = {
    zoom: 15,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.HYBRID
  };
        
  map = new google.maps.Map(document.getElementById("map_canvas"), options);
        
  //GEOCODER
  geocoder = new google.maps.Geocoder();
        
  marker = new google.maps.Marker({
    map: map,
    draggable: true
  });
				
}
		
$(document).ready(function() { 
         
  initialize();
				  
  $(function() {
    $("#'.$addressField.'").autocomplete({
      //This bit uses the geocoder to fetch address values
      source: function(request, response) {
        geocoder.geocode( {"address": request.term, "region": "it" }, function(results, status) {
          response($.map(results, function(item) {
            return {
              label:  item.formatted_address,
              value: item.formatted_address,
              latitude: item.geometry.location.lat(),
              longitude: item.geometry.location.lng()
            }
          }));
        })
      },
      //This bit is executed upon selection of an address
      select: function(event, ui) {
        $("#'.$latField.'").val(ui.item.latitude);
        $("#'.$lonField.'").val(ui.item.longitude);
        var location = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);
        marker.setPosition(location);
        map.setCenter(location);
      }
    });
  });
	
  //Add listener to marker for reverse geocoding
  google.maps.event.addListener(marker, "drag", function() {
    geocoder.geocode({"latLng": marker.getPosition()}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
          $("#'.$addressField.'").val(results[0].formatted_address);
          $("#'.$latField.'").val(marker.getPosition().lat());
          $("#'.$lonField.'").val(marker.getPosition().lng());
        }
      }
    });
  });
  
});
</script>';


if($this->preventSubmit) {

$output .= '<script type="text/javascript">
//    Prevent form submission if any field is empty
$(function() { 
        $(":submit").click(function(e) {                                  
                        if ($("#'.$latField.'").val().length == 0) {
                                $("#'.$latField.'").css("border", "1px solid red");
                                e.preventDefault();
                        }
                        if ($("#'.$lonField.'").val().length == 0) {
                                $("#'.$lonField.'").css("border", "1px solid red");
                                e.preventDefault();
                        }
                });         
});
</script>
';

}

        $this->output .= $output;
        //return $output;
        
    }
    
    /**
     * 
     * if the correspondin option is set, renders the input fields also.
     */
    public function renderFields() { // $options = array();
        
        if(!$this->renderFields) return false;
        
        $params = null;
        if($this->_actualOptions['latlonFieldsVisibility']  == 'readonly') {
            $params['readonly'] = 'readonly';
            $params['disabled'] = 'disabled';
        }
        if($this->_actualOptions['latlonFieldsVisibility'] == 'hidden') 
            $params['type'] = 'hidden';
        
        $output = '';
        
        if($this->_before) {
            $this->_before = false;   
            $output .= $this->Form->input($this->_actualOptions['modelName'].'.'.$this->_actualOptions['fields']['address']);
        } else {
            $output .= $this->Form->input($this->_actualOptions['modelName'].'.'.$this->_actualOptions['fields']['lat'], $params );
            $output .= $this->Form->input($this->_actualOptions['modelName'].'.'.$this->_actualOptions['fields']['lon'], $params );       
        }
        $this->output .= $output;
        //return $output;
    }


    /*
     * @todo: add a render whole form function (add required $options['action'] or $options['url'])
     */
//    public function renderForm($options = array()) {
//             
//        
//    }
    
}
?>
