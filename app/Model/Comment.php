<?php
App::uses('Sanitize', 'Utility');
/**
 * Class Comment
 *
 * @property Article $Article
 */
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
		'Tree',
		'Delete'
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
	 * @param $data
	 * @param $user_id
	 *
	 * @return array
	 */
    public function getBlockData($data, $user_id)
    {
        $cond = array(
            'conditions' => array(
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
    *
    * @param array $options
    *
    * @return boolean
    */
    public function beforeSave($options = array())
    {
        $this->data = Sanitize::clean($this->data, array(
            'encode' => false,
            'remove_html' => false
        ));

        if (!empty($this->data['Comment']['comment_text']))
            $this->data['Comment']['comment_text'] = stripslashes(str_replace('\n', '', $this->data['Comment']['comment_text']));

	    $full_clean = array(
			'author_name',
			'author_email',
			'author_website'
	    );
	    
	    foreach($full_clean as $field)
	    {
		    if (!empty($this->data['Comment'][$field]))
			    $this->data['Comment'][$field] = strip_tags(stripslashes(str_replace('\n', '', $this->data['Comment'][$field])));
	    }

        return true;
    }

    /**
    * Takes an array and returns a comment count for array of articles
    *
    * @param data
    * @return integer comment count
    */
    public function getCommentsCount($data)
    {
        if (!empty($data))
        {
            foreach($data as $key => $row)
            {
                if (!empty($row['Article']['id']))
                {
                    $data[$key]['Comment']['count'] = $this->find('count', array(
                        'conditions' => array(
                            'Comment.article_id' => $row['Article']['id'],
                            'Comment.active' => 1
                        )
                    ));
                }
            }
        }

        return $data;
    }
}