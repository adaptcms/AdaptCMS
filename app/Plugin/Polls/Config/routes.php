<?php

Router::connect('/polls', array('plugin' => 'polls', 'controller' => 'polls'));
Router::connect('/polls/list', array('plugin' => 'polls', 'controller' => 'polls', 'action' => 'all'));

$routes = array(
	'polls_list' => array(
		'route' => array(
			'plugin' => 'polls',
			'controller' => 'polls',
			'action' => 'all'
		)
	)
);
$routes = array_merge_recursive(Configure::read('current_routes'), $routes);
Configure::write('current_routes', $routes);