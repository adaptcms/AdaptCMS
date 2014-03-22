<?php

$params = '{"email":"","password":"","profile_id":""}';
$system_config = array(
	'admin_menu' => array(
		'controller' => 'google_analytics',
		'plugin' => 'google_analytics',
		'action' => 'index',
		'admin' => true
	),
	'admin_menu_label' => 'Google Analytics'
);

$config = json_decode($params, true);
Configure::write('GoogleAnalytics', array_merge($config, $system_config) );