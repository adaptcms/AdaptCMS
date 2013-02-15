<?php

class Ticket extends SupportTicketAppModel {
	public $name = 'PluginSupportTickets';
	public $belongsTo = array(
		'SendUser' => array(
			'className' => 'User'
		),
		'ReplyUser' => array(
			'className' => 'User'
		)
	);
	public $recursive = -1;
}