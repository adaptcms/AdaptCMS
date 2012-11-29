<?php

class PollVotingValue extends PollsAppModel
{
	public $name = 'PluginPollVotingValue';

	public $belongsTo = array(
        'Poll' => array(
            'className'    => 'Poll',
            'foreignKey'   => 'poll_id'
        ),
        'PollValue' => array(
        	'className'	   => 'PollValue',
        	'foreignKey'   => 'value_id'
        );
	);
}