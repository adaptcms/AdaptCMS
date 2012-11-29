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

    public function getModuleData($data, $user_id)
    {
        $cond = array(
            'conditions' => array(
                'Article.deleted_time' => '0000-00-00 00:00:00',
                'Article.status !=' => 0,
                'Article.publish_time <=' => date('Y-m-d H:i:s')
            ),
            'contain' => array(
                'Category',
                'User'
            ),
            'limit' => $data['limit']
        );

        if (!empty($data['order_by'])) {
            if ($data['order_by'] == "rand") {
                $data['order_by'] = 'RAND()';
            }

            $cond['order'] = 'Article.'.$data['order_by'].' '.$data['order_dir'];
        }

        if (!empty($data['data'])) {
            $cond['conditions']['Article.id'] = $data['data'];
        }

        return $this->find('all', $cond);
    }
}