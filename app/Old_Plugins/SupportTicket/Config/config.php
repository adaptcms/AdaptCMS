<?php

$params = '{"captcha_for_guests":"1"}';
$system_config = array(
	'admin_menu' => array(
		'plugin' => 'support_ticket',
		'controller' => 'ticket_categories',
		'action' => 'index',
		'admin' => true
	),
	'admin_menu_label' => 'Support Tickets'
);

$config = json_decode($params, true);
Configure::write('SupportTicket', array_merge($config, $system_config) );
?>