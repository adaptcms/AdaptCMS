<?php
class Field extends AppModel {

	public $name = "Field";
	public $belongsTo = array(
    	'Category' => array(
        	'className'    => 'Category',
        	'foreignKey'   => 'category_id',
        	'fields' => 'slug'
        )
    );
    public $hasMany = array(
    	'ArticleValue' => array(
    		'className' => 'ArticleValue',
    		'foreignKey' => 'field_id'
    		)
    );
    public $validate = array(
    'title' => array(
            'rule' => array('notEmpty')
        ),
    'field_type' => array(
            'rule' => array('notEmpty')
        ),
    'field_order' => array(
            'rule' => array('notEmpty')
        )
    );
    public $recursive = -1;
}