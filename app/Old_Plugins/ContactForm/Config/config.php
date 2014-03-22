<?php

$params = '{"name_of_page":"Contact Us","captcha_for_guests":"1","submissions_sent_to":"","email_subject":"Contact Form Submission","success_message":"Your email has been sent. We will respond within 5-7 business days."}';
$system_config = array(
	'admin_menu' => array(
		'plugin' => 'contact_form',
		'controller' => 'contact',
		'action' => 'index',
		'admin' => false
	),
	'admin_menu_label' => 'Contact Form'
);

$config = json_decode($params, true);
Configure::write('ContactForm', array_merge($config, $system_config) );