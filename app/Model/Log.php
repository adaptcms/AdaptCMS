<?php

class Log extends AppModel{
	public $name = 'Log';
	public $belongsTo = array(
    	'User' => array(
        	'className'    => 'User',
        	'foreignKey'   => 'user_id'
        )
    );
}