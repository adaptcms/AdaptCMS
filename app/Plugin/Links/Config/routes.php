<?php
$routes = array(
	'links_apply' => array(
		'route' => array(
			'plugin' => 'links',
			'controller' => 'links',
			'action' => 'apply'
		)
	)
);
$routes = array_merge_recursive(Configure::read('current_routes'), $routes);
Configure::write('current_routes', $routes);