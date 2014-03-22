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

$routes = array(
	'adaptbb_forums' => array(
		'route' => array(
			'controller' => 'forums',
			'plugin' => 'adaptbb',
			'action' => 'index'
		)
	),
	'adaptbb_view_topic' => array(
		'route' => array(
			'plugin' => 'adaptbb',
			'controller' => 'forum_topics',
			'action' => 'view'
		),
		'params' => array(
			'slug' => 'slug',
			'forum_slug' => 'forum_slug'
		),
		'key' => 'ForumTopic'
	),
	'adaptbb_view_forum' => array(
		'route' => array(
			'controller' => 'forums',
			'plugin' => 'adaptbb',
			'action' => 'view'
		),
		'params' => array(
			'slug' => 'slug'
		),
		'key' => 'Forum'
	),
	'adaptbb_add_topic' => array(
		'route' => array(
			'plugin' => 'adaptbb',
			'controller' => 'forum_topics',
			'action' => 'add'
		),
		'params' => array(
			'slug' => 'slug'
		),
		'key' => 'Forum'
	),
	'adaptbb_topic_change_status' => array(
		'route' => array(
			'plugin' => 'adaptbb',
			'controller' => 'forum_topics',
			'action' => 'change_status'
		),
		'params' => array(
			'id' => 'id'
		),
		'key' => 'ForumTopic'
	),
	'adaptbb_topic_edit' => array(
		'route' => array(
			'plugin' => 'adaptbb',
			'controller' => 'forum_topics',
			'action' => 'edit'
		),
		'params' => array(
			'id' => 'id'
		),
		'key' => 'ForumTopic'
	),
	'adaptbb_post_edit' => array(
		'route' => array(
			'plugin' => 'adaptbb',
			'controller' => 'forum_posts',
			'action' => 'ajax_edit'
		),
		'params' => array(
			'id' => 'id'
		),
		'key' => 'ForumPost'
	),
	'adaptbb_post_delete' => array(
		'route' => array(
			'plugin' => 'adaptbb',
			'controller' => 'forum_posts',
			'action' => 'delete'
		),
		'params' => array(
			'id' => 'id'
		),
		'key' => 'ForumPost'
	),
	'adaptbb_topic_delete' => array(
		'route' => array(
			'plugin' => 'adaptbb',
			'controller' => 'forum_topics',
			'action' => 'delete'
		),
		'params' => array(
			'id' => 'id'
		),
		'key' => 'ForumPost'
	)
);
$routes = array_merge_recursive(Configure::read('current_routes'), $routes);
Configure::write('current_routes', $routes);