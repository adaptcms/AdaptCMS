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
    public $hasMany = array(
        'ArticleValue',
        'Comment'
    );
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

            $data[$key]['Comments'] = $this->Comment->find('count', array(
                'conditions' => array(
                    'Comment.article_id' => $row['Article']['id']
                )
            ));
        }

        return $data;
    }

    public function getBlockData($data, $user_id)
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
            )
        );

        if (!empty($data['limit'])) {
            $cond['limit'] = $data['limit'];
        }

        if (!empty($data['order_by'])) {
            if ($data['order_by'] == "rand") {
                $data['order_by'] = 'RAND()';
            }

            $cond['order'] = 'Article.'.$data['order_by'].' '.$data['order_dir'];
        }

        if (!empty($data['data'])) {
            $cond['conditions']['Article.id'] = $data['data'];
        }

        if (!empty($data['category_id'])) {
            $cond['conditions']['Category.id'] = $data['category_id'];
        }

        return $this->getAllRelatedArticles($this->find('all', $cond));
    }

    public function getBlockCustomOptions($data)
    {
        $view = new View();
        $categories = $this->Category->find('list');

        $data = $view->element('article_custom_options', array(
            'categories' => $categories, 
            'id' => (!empty($data['category_id']) ? $data['category_id'] : '') 
        ));

        return $data;
    }

    public function getSearchParams( $q )
    {
        return array(
            'conditions' => array(
                'OR' => array(
                    'Article.title LIKE' => '%' . $q . '%',
                    'ArticleValue.data LIKE' => '%' . $q . '%'
                )
            ),
            'contain' => array(
                'ArticleValue' => array(
                    'File',
                    'Field'
                ),
                'Category',
                'User'
            ),
            'joins' => array(
                array(
                    'table' => 'article_values',
                    'alias' => 'ArticleValue',
                    'type' => 'inner',
                    'conditions' => array(
                        'Article.id = ArticleValue.article_id'
                    )
                )
            ),
            'permissions' => array(
                'controller' => 'articles',
                'action' => 'view'
            ),
            'group' => 'Article.id'
        );
    }

    public function beforeSave()
    {
        if (!empty($this->data['File']) && !empty($this->data['Files']))
        {
            $this->data['File'] = array_merge($this->data['File'], $this->data['Files']);
        } elseif (!empty($this->data['Files']))
        {
            $this->data['File'] = $this->data['Files'];
        }

        if (!empty($this->data['Article']['title']))
        {
            $this->data['Article']['slug'] = $this->slug($this->data['Article']['title']);
        }
        
        return true;
    }

    public function afterFind($results)
    {
        if (empty($results))
        {
            return;
        }

        foreach($results as $key => $result)
        {
            if (!empty($result['ArticleValue']) && is_array($result['ArticleValue']))
            {
                foreach($result['ArticleValue'] as $value)
                {
                    if (!empty($value['Field']))
                    {
                        $json = json_decode($value['data'], true);

                        if (empty($json))
                        {
                            $results[$key]['Data'][$value['Field']['title']] = $value['data'];
                        } else {
                            $results[$key]['Data'][$value['Field']['title']]['data'] = $json;
                            $results[$key]['Data'][$value['Field']['title']]['list'] = implode(', ', $json);
                        }
                    }
                }
            }

            if (!empty($result['Article']['tags']))
            {
                $results[$key]['Article']['tags'] = json_decode($result['Article']['tags'], true);
                $results[$key]['Article']['tags_list'] = implode(', ', $results[$key]['Article']['tags']);
            }
        }

        return $results;
    }
}