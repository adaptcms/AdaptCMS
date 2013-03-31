<?php
Router::connect('/forums', array(
	'controller' => 'forums',
	'plugin' => 'adaptbb', 
	'action' => 'index'
));

Router::connect('/forums/category/:slug', array(
	'controller' => 'forum_categories',
	'plugin' => 'adaptbb', 
	'action' => 'view',
	'slug' => '*'
));

Router::connect('/forums/:slug', array(
	'controller' => 'forums',
	'plugin' => 'adaptbb', 
	'action' => 'view',
	'slug' => '*'
));

Router::connectNamed(array('sort', 'direction'));
Router::connect('/forums/:slug/:sort/:direction', array(
	'controller' => 'forums',
	'plugin' => 'adaptbb', 
	'action' => 'view',
	'slug' => '*',
	'sort' => '*',
	'direction' => '*'
));

Router::connect('/forums/:slug/new', array(
	'controller' => 'forum_topics',
	'plugin' => 'adaptbb', 
	'action' => 'add',
	'slug' => '*'
));

Router::connect('/forums/:forum_slug/:slug', array(
	'controller' => 'forum_topics',
	'plugin' => 'adaptbb', 
	'action' => 'view',
	'forum_slug' => '*',
	'slug' => '*'
));

Router::connect('/forums/:forum_slug/:slug/*', array(
	'controller' => 'forum_topics',
	'plugin' => 'adaptbb', 
	'action' => 'view',
	'forum_slug' => '*',
	'slug' => '*'
));