<?php

$params = '[]';
$system_config = array(
	'admin_menu' => array(
		'controller' => 'links',
		'plugin' => 'links',
		'action' => 'index',
		'admin' => true
	)
);

$config = json_decode($params, true);
Configure::write('Links', array_merge($config, $system_config) );
?>