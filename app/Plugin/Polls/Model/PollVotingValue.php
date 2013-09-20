<?php
App::uses('PollsAppModel', 'Polls.Model');
/**
 * Class PollVotingValue
 */
class PollVotingValue extends PollsAppModel
{
	public $useTable = 'plugin_poll_voting_values';

	public $belongsTo = array(
        'Poll' => array(
            'className'    => 'Polls.Poll',
            'foreignKey'   => 'poll_id'
        ),
        'PollValue' => array(
        	'className'	   => 'Polls.PollValue',
        	'foreignKey'   => 'value_id'
        ),
		'User' => array(
			'className'    => 'User',
			'foreignKey'   => 'user_id'
		),
	);
}