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
	 * 3.0 Routes
	 */
	Router::connect('/admin', array(
		'controller' => 'pages', 
		'action' => 'admin'
	));
	
	Router::connect('/category/*', array(
		'controller' => 'categories', 
		'action' => 'view'
	));
	Router::connect('/article/:slug', array(
		'controller' => 'articles',
		'action' => 'view',
		'slug' => '*'
	));
	Router::connect('/article/:id/:slug', array(
		'controller' => 'articles', 
		'action' => 'view',
		'id' => '*',
		'slug' => '*'
	));
	Router::connect('/tag/*', array(
		'controller' => 'articles', 
		'action' => 'tag'
	));
	Router::connect('/users/profile/*', array(
		'controller' => 'users', 
		'action' => 'profile'
	));

	Router::connect('/register', array('controller' => 'users', 'action' => 'register'));
	Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/logout', array('controller' => 'users', 'action' => 'logout'));

	/**
	* AdaptCMS 2.0.x Routing
	*/
	Router::connect('/article/:id/:category/:slug', array(
		'controller' => 'install', 
		'action' => 'old',
		'id' => '*',
		'slug' => '*',
		'category' => '*',
		'type' => 'article'
	));

	Router::connect('/section/:slug', array(
		'controller' => 'install', 
		'action' => 'old',
		'type' => 'category',
		'slug' => '*'
	));

	Router::connect('/page/:id/:slug', array(
		'controller' => 'install', 
		'action' => 'old',
		'type' => 'page',
		'id' => '*',
		'slug' => '*'
	));

    Router::connect('/page/:slug', array(
        'controller' => 'install',
        'action' => 'old',
        'type' => 'page',
        'slug' => '*'
    ));

	Router::connect('/profile/', array(
		'controller' => 'users', 
		'action' => 'profile'
	));

	/**
	* AdaptCMS 1.x Routing
	*/

	Router::connect('/article-:id-:slug-:category', array(
		'controller' => 'install', 
		'action' => 'old',
		'id' => '*',
		'slug' => '*',
		'category' => '*',
		'type' => 'article'
	), array('id' => '[0-9]+'));

	if (Configure::check('Plugins.list'))
	{
		foreach (Configure::read('Plugins.list') as $plugin)
		{
			$path = APP . DS . 'Plugin' . DS . $plugin . DS . 'Config' . DS . 'routes.php';

			if (file_exists($path))
			{
				include_once($path);
			}
		}
	}

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
