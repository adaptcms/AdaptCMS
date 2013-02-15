<?php

class Poll extends PollsAppModel
{
	public $name = 'PluginPoll';
	public $belongsTo = array(
        'Article' => array(
            'className'    => 'Article',
            'foreignKey'   => 'article_id'
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
	);
	public $hasMany = array(
		'PollValue' => array(
            'className' => 'PluginPollValue'
        ),
        'PollVotingValue' => array(
            'className' => 'PluginPollVotingValue'
        )
	);
	public $recursive = -1;

    public function getBlockData($data, $user_id)
    {
        $cond = array(
            'conditions' => array(
                'Poll.deleted_time' => '0000-00-00 00:00:00'
            ),
            'contain' => array(
                'PollValue'
            )
        );

        if (!empty($data['limit'])) {
            $cond['limit'] = $data['limit'];
        }

        if (!empty($data['order_by'])) {
            if ($data['order_by'] == "rand") {
                $data['order_by'] = 'RAND()';
            }

            $cond['order'] = 'Poll.'.$data['order_by'].' '.$data['order_dir'];
        }

        if (!empty($data['data'])) {
            $cond['conditions']['Poll.id'] = $data['data'];
        }

        $find = $this->find('all', $cond);

        $results = array();

        foreach($find as $key => $row) {
            $results[$key] = $row;
            $results[$key]['Block'] = $data;

            $results[$key]['Poll']['can_vote'] = $this->canVote($row, $user_id);
            $results[$key] = $this->totalVotes($results[$key]);

            foreach($row['PollValue'] as $option) {
                $results[$key]['options'][$option['id']] = $option['title'];
            }
        }

        if (!empty($results) && count($results) == 1) {
            $results = $results[0];
            unset($results[0]);
        }

        return $results;
    }

    public function totalVotes($data)
    {
        $data['Poll']['total_votes'] = 0;
        foreach($data['PollValue'] as $key => $row) {
            $data['Poll']['total_votes'] = $data['Poll']['total_votes'] + $row['votes'];
        }

        return $data;
    }

    public function canVote($row, $user_id)
    {
        $count = $this->find('first', array(
            'conditions' => array(
                'Poll.id' => $row['Poll']['id']
            ),
            'contain' => array(
                'PollVotingValue' => array(
                    'conditions' => array(
                        'OR' => array(
                            'PollVotingValue.user_id' => $user_id,
                            'PollVotingValue.user_ip' => $_SERVER['REMOTE_ADDR']
                        )
                    )
                )
            )
        ));
        
        if (count($count['PollVotingValue']) == 0) {
            return true;
        } else {
            return false;
        }
    }
}