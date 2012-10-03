<?php

class File extends AppModel{
	public $name = 'File';
	public $hasMany = array('ArticleValue');
	public $actsAs = array(
		'Upload'
    );
    public $validate = array(
    'filename' => array(
            'rule' => array(
            	'isUnique',
            )
        )
    );
    public $recursive = -1;
}