<?php

$params = '[]';
$system_config = array(
	'admin_menu' => array(
		'controller' => 'polls',
		'plugin' => 'polls',
		'action' => 'index',
		'admin' => true
	)
);

$config = json_decode($params, true);
Configure::write('Polls', array_merge($config, $system_config) );
?>