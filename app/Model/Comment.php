<?php

class Comment extends AppModel {
	public $name = 'Comment';
	public $belongsTo = array(
		'Article' => array(
			'className' => 'Article',
			'foreignKey' => 'article_id'
			),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
			)
		);
}