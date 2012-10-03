<?php

class Category extends AppModel {
	
	public $name = 'Category';
	public $hasMany = array('Field', 'Article');
    public $validate = array(
    	'title' => array(
			array(
				'rule' => 'notEmpty',
				'message' => 'Category title cannot be empty'
			),
			array(
				'rule' => 'isUnique',
				'message' => 'Category title has already been used'
			)
        )
    );
    public $recursive = -1;
}