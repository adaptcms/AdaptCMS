<?php

class Poll extends AppModel {
	public $name = 'PluginPoll';
	public $belongsTo = array(
        'Article' => array(
            'className'    => 'Article',
            'foreignKey'   => 'article_id'
        )
	);
	public $hasMany = array(
		'PluginPollValue'
	);
	public $recursive = -1;

}