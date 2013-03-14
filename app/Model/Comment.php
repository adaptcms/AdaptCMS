<?php
App::uses('Sanitize', 'Utility');

class Comment extends AppModel
{
    /**
    * Name of our Model, table will look like 'adaptcms_comments'
    */
	public $name = 'Comment';

    /**
    * Every comment belongs to an Article and can belong to a user
    */
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

    /**
    * This behaviour allows for multiple levels of comments.
    */
	public $actsAs = array(
		'Tree'
	);

    /**
    * Our validation rules
    */
    public $validate = array(
        'article_id' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'No Article specified'
            )
        ),
        'comment_text' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Comment cannot be empty'
            )
        )
    );

    /**
    * This works in conjuction with the Block feature. Doing a simple find with any conditions filled in by the user that
    * created the block. This is customizable so you can do a contain of related data if you wish.
    *
    * @return associative array
    */
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

    /**
    * Cleans out user input, html is allowed per setting and removed in controller
    */
    public function beforeSave()
    {
        $this->data = Sanitize::clean($this->data, array(
            'encode' => false,
            'remove_html' => false
        ));

        return true;
    }
}