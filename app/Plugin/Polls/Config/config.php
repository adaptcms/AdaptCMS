<?php
App::uses('CakeEventManager', 'Event');
App::uses('PollsEventListener', 'Polls.EventListener');

$params = '[]';
$system_config = array(
	'admin_menu' => array(
		'controller' => 'polls',
		'plugin' => 'polls',
		'action' => 'index',
		'admin' => true
	)
);

$config = json_decode($params, true);
Configure::write('Polls', array_merge($config, $system_config) );

if (CakePlugin::loaded('Polls')) {
	CakeEventManager::instance()->attach(
		new PollsEventListener(),
		'Controller.Articles.view.beforeRender'
	);
}