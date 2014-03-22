<?php

class Log extends AppModel{
    /**
    * Name of our Model, table will look like 'adaptcms_logs'
    */
	public $name = 'Log';

	/**
	* Relationship to a user, if applicable
	*/
	public $belongsTo = array(
    	'User' => array(
        	'className'    => 'User',
        	'foreignKey'   => 'user_id'
        )
    );
}