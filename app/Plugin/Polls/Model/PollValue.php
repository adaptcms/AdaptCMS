<?php

class PollValue extends PollsAppModel
{
	public $name = 'PluginPollValue';
	public $belongsTo = array(
        'Poll' => array(
            'className'    => 'Poll',
            'foreignKey'   => 'poll_id'
        )
	);
	
}