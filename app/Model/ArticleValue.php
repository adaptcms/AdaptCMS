<?php

class ArticleValue extends AppModel {

	public $name = 'ArticleValue';
	public $belongsTo = array(
        'Article' => array(
            'className'    => 'Article',
            'foreignKey'   => 'article_id'
        ),
        'Field' => array(
            'className' => 'Field',
            'foreignKey' => 'field_id'
        ),
        'File' => array(
            'className' => 'File',
            'foreignKey' => 'file_id'
        )
    );
}