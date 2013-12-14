<?php
Router::connect('/contact', array(
	'plugin' => 'contact_form',
	'controller' => 'contact',
	'action' => 'index'
));

$routes = array(
	'contact_form' => array(
		'route' => array(
			'plugin' => 'contact_form',
			'controller' => 'contact',
			'action' => 'index'
		)
	)
);
$routes = array_merge_recursive(Configure::read('current_routes'), $routes);
Configure::write('current_routes', $routes);