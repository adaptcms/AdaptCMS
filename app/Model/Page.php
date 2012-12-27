<?php

class Page extends AppModel {
	public $name = "Page";
    public $validate = array(
    	'title' => array(
			array(
				'rule' => 'notEmpty',
				'message' => 'Page title cannot be empty'
			),
			array(
				'rule' => 'isUnique',
				'message' => 'Page title has already been used'
			)
        ),
    	'content' => array(
            'rule' => array(
            	'notEmpty'
            )
        )
    );
}