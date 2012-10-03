<?php

class RelatedArticle extends AppModel {
	public $name = 'RelatedArticle';
	public $belongsTo = array(
        'Article1' => array(
            'className'    => 'Article1',
            'foreignKey'   => 'article_id_1'
        ),
        'Article2' => array(
        	'className'	   => 'Article2',
        	'foreignKey'   => 'article_id_2'
        )
    );
}