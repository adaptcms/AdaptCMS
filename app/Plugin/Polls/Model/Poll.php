<?php
App::uses('PollsAppModel', 'Polls.Model');
/**
 * Class Poll
 *
 * @property PollVotingValue $PollVotingValue
 * @property PollValue $PollValue
 * @property Article $Article
 */
class Poll extends PollsAppModel
{
	/**
	 * @var string
	 */
	public $useTable = 'plugin_polls';

	/**
	 * @var array
	 */
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

	/**
	 * @var array
	 */
	public $hasMany = array(
		'PollValue' => array(
            'className' => 'Polls.PollValue',
			'foreignKey' => 'poll_id',
            'dependent' => true
        ),
        'PollVotingValue' => array(
            'className' => 'Polls.PollVotingValue',
	        'foreignKey' => 'poll_id',
            'dependent' => true
        )
	);

	/**
	 * @var array
	 */
	public $actsAs = array('Delete');

	/**
	 * Get Block Data
	 *
	 * @param array $data
	 * @param integer $user_id
	 *
	 * @return array
	 */
	public function getBlockData($data, $user_id)
    {
        $cond = array(
            'contain' => array(
                'PollValue'
            )
        );

	    $schema = $this->schema();
	    if (!empty($schema['start_date']) && !empty($schema['end_date'])) {
		    $cond['conditions']['OR']['Poll.start_date'] = null;
		    $cond['conditions']['OR']['Poll.end_date'] = null;
		    $cond['conditions']['OR']['AND']['Poll.start_date <='] = date('Y-m-d');
		    $cond['conditions']['OR']['AND']['Poll.end_date >='] = date('Y-m-d');
	    }

        if (!empty($data['limit'])) {
            $cond['limit'] = $data['limit'];
        }

        if (!empty($data['order_by'])) {
            if ($data['order_by'] == "rand") {
                $data['order_by'] = 'RAND()';
            }

            $cond['order'] = 'Poll.'.$data['order_by'].' '.$data['order_dir'];
        }

        if (!empty($data['data']))
            $cond['conditions']['Poll.id'] = $data['data'];

	    if (!empty($data['article_id']))
		    $cond['conditions']['Poll.article_id'] = $data['article_id'];

        $find = $this->find('all', $cond);

        $results = array();

        foreach($find as $key => $row) {
            $results[$key] = $row;
            $results[$key]['Block'] = $data;
        }

	    $results = $this->canVote($results, $user_id);

        if (!empty($results) && count($results) == 1) {
            $results = $results[0];
            unset($results[0]);
        }

        return $results;
    }

	/**
	 * Total Votes
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function totalVotes($data)
    {
        $data['Poll']['total_votes'] = 0;

	    if (!empty($data['PollValue']))
	    {
	        foreach($data['PollValue'] as $row) {
	            $data['Poll']['total_votes'] = $data['Poll']['total_votes'] + $row['votes'];
	        }
	    }

        return $data;
    }

	/**
	 * Can Vote
	 *
	 * @param array $results
	 * @param integer$user_id
	 *
	 * @return array
	 */
	public function canVote($results = array(), $user_id)
	{
		if (!empty($results))
		{
			if (empty($results[0]))
			{
				$single = true;
				$results[0] = $results;
			}

			$ip = $_SERVER['REMOTE_ADDR'];
			foreach($results as $key => $row)
			{
				$conditions['PollVotingValue.poll_id'] = $row['Poll']['id'];
				if (!empty($user_id)) {
					$conditions['OR'] = array(
						'PollVotingValue.user_id' => $user_id,
						'PollVotingValue.user_ip' => $ip
					);
				} else {
					$conditions['PollVotingValue.user_ip'] = $ip;
				}

				$count = $this->PollVotingValue->find('count', array(
					'conditions' => $conditions
				));

				$results[$key]['Poll']['can_vote'] = ($count == 0 ? true : false);
			}

			if (isset($single))
			{
				$results = $results[0];
				unset($results[0]);
			}
		}

		return $results;
	}

	/**
	 * After Find
	 *
	 * @param mixed $results
	 * @param bool $primary
	 *
	 * @return array
	 */
	public function afterFind($results, $primary = false)
	{
		if (!empty($results))
		{
			foreach($results as $key => $row)
			{
				if (empty($row['Poll']['total_votes']))
					$results[$key] = $this->totalVotes($row);

				if (!empty($row['PollValue']))
				{
					foreach($row['PollValue'] as $val => $option) {
						$results[$key]['options'][$option['id']] = $option['title'];

						if ($results[$key]['Poll']['total_votes'] == 0)
						{
							$results[$key]['PollValue'][$val]['percent'] = 0;
						}
						else
						{
							$results[$key]['PollValue'][$val]['percent'] = round($option['votes'] / $results[$key]['Poll']['total_votes'] * 100);
						}
					}
				}
			}
		}

		return $results;
	}

	/**
	 * Before Save
	 *
	 * @param array $options
	 * @return bool
	 */
	public function beforeSave($options = array())
	{
		if (!empty($this->data['Poll']['title'])) {
			if (empty($this->data['Poll']['start_date']))
				$this->data['Poll']['start_date'] = date('Y-m-d');

			if (empty($this->data['Poll']['end_date']))
				$this->data['Poll']['end_date'] = date('Y-m-d', strtotime('+100 year'));
		}

		return true;
	}
}