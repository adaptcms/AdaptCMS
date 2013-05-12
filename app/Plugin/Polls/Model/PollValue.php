<?php

class PollValue extends PollsAppModel
{
	public $name = 'PluginPollValue';

	public $belongsTo = array(
        'Poll' => array(
            'className'    => 'Polls.Poll',
            'foreignKey'   => 'poll_id'
        )
	);

	public $hasMany = array(
		'PollVotingValue' => array(
			'className' => 'Polls.PollVotingValue',
			'foreignKey' => 'value_id'
		)
	);
}