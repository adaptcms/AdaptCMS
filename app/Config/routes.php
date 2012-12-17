<?php
	Router::mapResources(array('categories', 'articles'));
	Router::parseExtensions();
	// Router::parseExtensions('rss');
	
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Alpha Routes
 */
	Router::connect('/admin', array('controller' => 'pages', 'action' => 'display', 'admin'));
	
	Router::connect('/category/*', array('controller' => 'categories', 'action' => 'view'));
	Router::connect('/article/*', array('controller' => 'articles', 'action' => 'view'));
	Router::connect('/tag/:tag', array(
		'controller' => 'articles', 
		'action' => 'view_by_tag',
		'pass' => array('tag')
	));

	Router::connect('/register', array('controller' => 'users', 'action' => 'register'));
	Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/logout', array('controller' => 'users', 'action' => 'logout'));

/**
 * Load all plugin routes.  See the CakePlugin documentation on 
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
