<?php

$params = '{"captcha_for_guests_submit_page":"1","text_on_success_submit":"We will review your link within 3-5 business days."}';
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