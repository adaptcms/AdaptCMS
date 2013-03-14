<?php

$params = '[]';
$system_config = array(
	'admin_menu' => array(
		'plugin' => 'adaptbb',
		'controller' => 'forums',
		'action' => 'index',
		'admin' => true
	),
	'admin_menu_label' => 'AdaptBB'
);

$config = json_decode($params, true);
Configure::write('Adaptbb', array_merge($config, $system_config) );
?>