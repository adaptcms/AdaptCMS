<?php
$params = '{"html_tags_allowed":"<strong>,<a>,<p>,<br>","num_posts_per_page_topic": 10,"num_topics_per_page_forum":5,"num_posts_hot_topic":10}';
$system_config = array(
	'admin_menu' => array(
		'plugin' => 'adaptbb',
		'controller' => 'forums',
		'action' => 'index',
		'admin' => true
	),
	'admin_menu_label' => 'AdaptBB'
);

$config = json_decode($params, true);
Configure::write('Adaptbb', array_merge($config, $system_config) );