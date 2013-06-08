<?php
App::uses('Sanitize', 'Utility');

class Message extends AppModel
{
    /**
    * Name of our Model, table will look like 'adaptcms_messages'
    */
	public $name = 'Message';

    /**
    * Every message has a sender and receiver, both having to be logged in users.
    */
	public $belongsTo = array(
		'Sender' => array(
			'className' => 'User',
			'foreignKey' => 'sender_user_id'
		),
		'Receiver' => array(
			'className' => 'User',
			'foreignKey' => 'receiver_user_id'
		)
	);

    /**
    * Validation rules, title and message cannot be empty.
    */
    public $validate = array(
    	'title' => array(
        	'rule' => array(
        		'notEmpty'
        	)
    	),
    	'message' => array(
            'rule' => array(
            	'notEmpty'
            )
        )
    );

    /**
    * Cleans out user input
    */
    public function beforeSave()
    {
        $this->data = Sanitize::clean($this->data, array(
            'encode' => false,
            'remove_html' => false
        ));

        if (!empty($this->data['Message']['message']))
            $this->data['Message']['message'] = stripslashes(str_replace('\n', '', $this->data['Message']['message']));

        return true;
    }
}