<?php

class Message extends AppModel
{
	public $name = 'Message';
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
}