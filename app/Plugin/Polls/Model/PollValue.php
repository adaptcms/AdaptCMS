<?php
App::uses('PollsAppModel', 'Polls.Model');
/**
 * Class PollValue
 */
class PollValue extends PollsAppModel
{
	public $useTable = 'plugin_poll_values';

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