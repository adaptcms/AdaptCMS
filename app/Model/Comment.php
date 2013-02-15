<?php

class Comment extends AppModel
{
	public $name = 'Comment';
	public $belongsTo = array(
		'Article' => array(
			'className' => 'Article',
			'foreignKey' => 'article_id'
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);
	public $actsAs = array(
		'Tree'
	);

    public function getBlockData($data, $user_id)
    {
        $cond = array(
            'conditions' => array(
                'Comment.deleted_time' => '0000-00-00 00:00:00',
                'Comment.active !=' => 0
            ),
            'contain' => array(
                'Article'
            )
        );

        if (!empty($data['limit'])) {
            $cond['limit'] = $data['limit'];
        }

        if (!empty($data['order_by'])) {
            if ($data['order_by'] == "rand") {
                $data['order_by'] = 'RAND()';
            }

            $cond['order'] = 'Comment.'.$data['order_by'].' '.$data['order_dir'];
        }

        if (!empty($data['data'])) {
            $cond['conditions']['Comment.id'] = $data['data'];
        }

        return $this->find('all', $cond);
    }
}