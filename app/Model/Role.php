<?php

class Role extends AppModel {
	public $name = 'Role';
    public $validate = array(
    	'title' => array(
			array(
				'rule' => 'notEmpty',
				'message' => 'Role title cannot be empty'
			),
			array(
				'rule' => 'isUnique',
				'message' => 'Role title has already been used'
			)
        )
    );

	public $hasMany = array(
		'User',
		'Permission'
	);
}