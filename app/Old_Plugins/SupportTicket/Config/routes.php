<?php
Router::connect('/support', array(
	'plugin' => 'support_ticket', 
	'controller' => 'tickets', 
	'action' => 'index'
));

Router::connect('/support/ticket/:id/:slug', array(
	'plugin' => 'support_ticket', 
	'controller' => 'tickets', 
	'action' => 'view',
	'id' => '*',
	'slug' => '*'
));