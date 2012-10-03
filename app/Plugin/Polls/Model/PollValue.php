<?php

class PluginPollValue extends AppModel {
	public $name = 'PluginPollValue';
	public $belongsTo = array(
        'Poll' => array(
            'className'    => 'Poll',
            'foreignKey'   => 'poll_id'
        )
	);
	
}