<?php
/**
  * Get an api_key and secret from facebook and fill in this content.
  * save the file to app/Config/facebook.php
  */

$params = '{"appId":"521513811192026","apiKey":"521513811192026","secret":"1e7e0a85209be4083606e83d2ea85b0f","cookie":"1","locale":"en_US"}';

$config = json_decode($params, true);
Configure::write('Facebook', $config );
?>