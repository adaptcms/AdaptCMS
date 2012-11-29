<?php

class Poll extends PollsAppModel
{
	public $name = 'PluginPoll';
	public $belongsTo = array(
        'Article' => array(
            'className'    => 'Article',
            'foreignKey'   => 'article_id'
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

    public function getModuleData($data, $user_id)
    {
        $cond = array(
            'conditions' => array(
                'Poll.deleted_time' => '0000-00-00 00:00:00'
            ),
            'contain' => array(
                'PollValue'
            ),
            'limit' => $data['limit']
        );

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
                $results[$key]['Poll']['can_vote'] = true;
            } else {
                $results[$key]['Poll']['can_vote'] = false;
            }

            $votes = 0;
            foreach($row['PollValue'] as $option) {
                $votes = $votes + $option['votes'];
                $results[$key]['options'][$option['id']] = $option['title'];
            }

            $results[$key]['Poll']['total_votes'] = $votes;
        }

        if (!empty($results) && count($results) == 1) {
            $results = $results[0];
            unset($results[0]);
        }

        return $results;
    }
}