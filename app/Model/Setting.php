<?php

class Setting extends AppModel {
	public $name = 'Setting';
    public $validate = array(
    	'title' => array(
			array(
				'rule' => 'notEmpty',
				'message' => 'Setting title cannot be empty'
			),
			array(
				'rule' => 'isUnique',
				'message' => 'Setting title has already been used'
			)
        )
    );

    public $hasMany = array(
    	'SettingValue'
    );
}