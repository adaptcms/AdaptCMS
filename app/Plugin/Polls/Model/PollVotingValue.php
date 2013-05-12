<?php

class PollVotingValue extends PollsAppModel
{
	public $name = 'PluginPollVotingValue';

	public $belongsTo = array(
        'Poll' => array(
            'className'    => 'Polls.Poll',
            'foreignKey'   => 'poll_id'
        ),
        'PollValue' => array(
        	'className'	   => 'Polls.PollValue',
        	'foreignKey'   => 'value_id'
        )
	);
}