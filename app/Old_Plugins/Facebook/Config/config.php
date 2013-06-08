<?php

$params = '{"appId":"","apiKey":"","secret":"","cookie":"1","locale":"en_US"}';
$system_config = array(
	'admin_menu' => false
);

$config = json_decode($params, true);
Configure::write('Facebook', array_merge($config, $system_config) );
?>