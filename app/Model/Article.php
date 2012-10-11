<?php

class Article extends AppModel {

	public $name = 'Article';
	public $belongsTo = array(
        'User' => array(
            'className'    => 'User',
            'foreignKey'   => 'user_id'
        ),
        'Category' => array(
            'className' => 'Category',
            'foreignKey' => 'category_id'
        )
    );
    public $actsAs = array(
        'Upload'
    );
    public $hasMany = array("ArticleValue");
    public $validate = array(
    'title' => array(
            'rule' => array('notEmpty')
        )
    );
    public $recursive = -1;

    public function getRelatedArticles($id, $related)
    {
        $find = $this->find('all', array(
            'conditions' => array(
                'OR' => array(
                    'Article.id' => json_decode($related),
                    'Article.related_articles LIKE' => '%"'.$id.'"%'
                )
            ),
            'contain' => array(
                'Category'
            )
        ));

        return $find;
    }

    public function getAllRelatedArticles($data)
    {
        foreach($data as $key => $row) {
            if (!empty($row['Article']['related_articles'])) {
                $data[$key]['RelatedArticles'] = $this->getRelatedArticles(
                    $row['Article']['id'], 
                    $row['Article']['related_articles']
                );
            }
        }

        return $data;
    }
}