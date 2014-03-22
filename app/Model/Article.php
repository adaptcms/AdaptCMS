<?php
/**
 * Class Article
 * @property ArticleValue $ArticleValue
 * @property Article $Article
 * @property Category $Category
 * @property User $User
 * @property Comment $Comment
 * @property ArticleRevision $ArticleRevision
 * @property Media $Media
 */
class Article extends AppModel
{
    /**
    * Name of our Model, table will look like 'adaptcms_articles'
    */
    public $name = 'Article';

    /**
    * Articles belong to a user (when added/edited) and a category
    */
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

    /**
    * Articles may have many article values and many comments related to it
    */
    public $hasMany = array(
        'ArticleValue' => array(
            'dependent' => true
        ),
	    'ArticleRevision' => array(
		    'dependent' => true
	    ),
        'Comment' => array(
            'dependent' => true
        )
    );

	/**
	 * Articles may belong to many media albums. Setting unique to 'keepExisting' means that if
	 * article #1 belongs to media #1 and then is added to media #2, cake will keep the first record
	 * and not delete/re-add it.
	 *
	 * @var array
	 */
	public $hasAndBelongsToMany = array(
		'Media' => array(
			'className' => 'Media',
			'joinTable' => 'media_articles',
			'unique' => 'keepExisting',
			'with' => 'MediaArticle'
		)
	);

    /**
    * Articles must have a title
    */
    public $validate = array(
        'title' => array(
            'rule' => array(
                'notEmpty'
            )
        )
    );

    /**
     * @var array
     */
    public $actsAs = array(
	    'Slug',
	    'Delete'
    );

    /**
     * A convenience function that will retrieve all related articles
     *
     * @param integer $id
     * @param string $related
     * @param array $categories
     * @param array $users
     * @param array $fields
     * @param array $files
     * @return array of related articles
     */
    public function getRelatedArticles($id, $related, $categories = array(), $users = array(), $fields = array(), $files = array())
    {
        $data = array();
	    $find = array();

        if (!empty($related))
        {
            $temp = json_decode($related, true);

            if (!empty($temp[0]) && empty($temp[1]))
            {
                $related = $temp[0];
            }
            else
            {
                $related = $temp;
            }
        }

	    if (!empty($related)) {
	        $find = $this->find('all', array(
	            'conditions' => array(
	                'OR' => array(
	                    'Article.id' => $related,
	                    'Article.related_articles LIKE' => '%"'.$id.'"%'
	                )
	            )
	        ));

	        if (!empty($find))
	            $find = $this->getAllRelatedArticles(
	                $find,
	                true,
	                $categories,
	                $users,
	                $fields,
	                $files
	            );

	        if (!empty($find))
	        {
	            if (!empty($fields))
	            {
	                $new_fields = Set::extract('{n}.ArticleValue.{n}.field_id', $find);

	                if (!empty($new_fields))
	                {
	                    $temp_fields = array();
	                    foreach($new_fields as $val)
	                    {
		                    if (!empty($val) && is_array($val)) {
		                        foreach($val as $value)
		                        {
		                            $temp_fields[$value] = $value;
		                        }
		                    }
	                    }

	                    $fields = array_unique(array_merge(
	                        $fields,
	                        $temp_fields
	                    ));
	                }

	                $field_data = $this->ArticleValue->Field->find('all', array(
	                    'conditions' => array(
	                        'Field.id' => $fields
	                    )
	                ));

	                $field_data = $this->arrayKeyById($field_data, 'Field');
	            }

	            if (!empty($field_data))
	            {
	                foreach($find as $key => $row)
	                {
	                    if (!empty($row['ArticleValue']))
	                    {
	                        foreach($row['ArticleValue'] as $i => $value)
	                        {
	                            $field_id = $value['field_id'];

	                            if (!empty($field_data[$field_id]))
	                                $find[$key]['ArticleValue'][$i]['Field'] = $field_data[$field_id];
	                        }
	                    }
	                }
	            }

	            $find = $this->convertFieldData($find);


	            foreach($find as $row)
	            {
	                $data[$row['Category']['slug']][] = $row;
	            }
	        }
	    }

        return array(
            'all' => $find,
            'category' => $data
        );
    }

    /**
     * Another convenience function, this time it calls the above getRelatedArticles function
     * and grabs comments.
     *
     * @param array $data
     * @param bool $loop
     * @param array $categories
     * @param array $users
     * @param array $fields
     * @param array $files
     * @internal param \to $data parse through
     * @return array
     */
    public function getAllRelatedArticles($data = array(), $loop = false, $categories = array(), $users = array(), $fields = array(), $files = array())
    {
        if (empty($data))
            return array();

        foreach($data as $key => $row)
        {
            $article_id = $row['Article']['id'];

            if (!empty($row['Article']['category_id']) && empty($row['Category']))
                $category_id = $row['Article']['category_id'];

            if (!empty($row['Article']['user_id']) && empty($row['User']))
                $user_id = $row['Article']['user_id'];

            if (!empty($category_id) && empty($categories[$category_id]))
                $categories[$category_id] = $this->Category->find('first', array(
                   'conditions' => array(
                       'Category.id' => $category_id
                   )
                ));

            if (!empty($category_id) && !empty($categories[$category_id]))
                $data[$key]['Category'] = $categories[$category_id]['Category'];

            if (!empty($user_id) && empty($users[$user_id]))
                $users[$user_id] = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id' => $user_id
                    )
                ));

            if (!empty($user_id) && !empty($users[$user_id]))
                $data[$key]['User'] = $users[$user_id]['User'];

            if (empty($row['ArticleValue']))
            {
                $article_values = $this->ArticleValue->find('all', array(
                    'conditions' => array(
                        'ArticleValue.article_id' => $article_id
                    )
                ));

                if (!empty($article_values))
                {
                    foreach($article_values as $i => $value)
                    {
                        $file_id = $value['ArticleValue']['file_id'];

                        if (!empty($file_id) && empty($files[$file_id]))
                        {
                            $files[$file_id] = $this->ArticleValue->File->find('first', array(
                               'conditions' => array(
                                   'File.id' => $file_id
                               ) 
                            ));

                            if (!empty($files[$file_id]))
                                $article_values[$i]['ArticleValue']['File'] = $files[$file_id]['File'];
                        }
                        elseif (!empty($files[$file_id]))
                        {
                            $article_values[$i]['ArticleValue']['File']= $files[$file_id]['File'];
                        }


                        if (!empty($value['ArticleValue']['field_id']))
                            $fields[$value['ArticleValue']['field_id']] = $value['ArticleValue']['field_id'];

                        $data[$key]['ArticleValue'][$i] = $article_values[$i]['ArticleValue'];
                    }
                }
            }

            $data[$key]['CommentsCount'] = $this->Comment->find('count', array(
                'conditions' => array(
                    'Comment.article_id' => $article_id,
                    'Comment.active' => 1
                )
            ));

            if (empty($loop))
            {
                $data[$key]['RelatedArticles'] = $this->getRelatedArticles(
                    $row['Article']['id'],
                    !empty($row['Article']['related_articles']) ? $row['Article']['related_articles'] : array(),
                    $categories,
                    $users,
                    $fields,
                    $files
                );
            }
        }

        if (!empty($fields) && empty($loop))
        {
            $field_data = $this->ArticleValue->Field->find('all', array(
               'conditions' => array(
                    'Field.id' => $fields
               )
            ));

            $field_data = $this->arrayKeyById($field_data, 'Field');
        }

        if (!empty($field_data))
        {
            foreach($data as $key => $row)
            {
                if (!empty($row['ArticleValue']))
                {
                    foreach($row['ArticleValue'] as $i => $value)
                    {
                        $field_id = $value['field_id'];

                        if (!empty($field_data[$field_id]))
                            $data[$key]['ArticleValue'][$i]['Field'] = $field_data[$field_id];
                    }
                }
            }
        }

        $data = $this->convertFieldData($data);

        return $data;
    }

    /**
     * This works in conjuction with the Block feature. Doing a simple find with any conditions filled in by the user that
     * created the block. This is customizable so you can do a contain of related data if you wish.
     *
     * The function in this model will also match for a category filtering of articles and retrieve related articles/comments.
     *
     * @param $data
     * @param $user_id
     * @return array
     */
    public function getBlockData($data, $user_id)
    {
        $cond = array(
            'conditions' => array(
                'Article.status !=' => 0,
                'Article.publish_time <=' => date('Y-m-d H:i:s')
            ),
            'contain' => array(
                'Category',
                'User'
            )
        );

        if (!empty($data['limit']))
        {
            $cond['limit'] = $data['limit'];
        }

        if (!empty($data['order_by']))
        {
            if ($data['order_by'] == "rand")
            {
                $data['order_by'] = 'RAND()';
            }

            $cond['order'] = 'Article.'.$data['order_by'].' '.$data['order_dir'];
        }

        if (!empty($data['data']))
        {
            $cond['conditions']['Article.id'] = $data['data'];
        }

        if (!empty($data['category_id']))
        {
            $cond['conditions']['Category.id'] = $data['category_id'];
        }

        return $this->getAllRelatedArticles($this->find('all', $cond));
    }

    /**
    * For block support, articles allow filtering by category. To enable this we call the view and pass a list of
    * categories to this element and get the resulting code, passing it back to blocks. It's not proper MVC, but
    * I don't know another way around it.
    *
    * @param data
    * @return string containing HTML to display
    */
    public function getBlockCustomOptions($data)
    {
        $view = new AdaptcmsView();
        $categories = $this->Category->find('list');

        $data = $view->element('article_custom_options', array(
            'categories' => $categories, 
            'id' => (!empty($data['category_id']) ? $data['category_id'] : '') 
        ));

        return $data;
    }

    /**
     * Hooking up to the search feature, the params passed back will look for articles
     * based on the search param, include related data and pass back a permission that is required
     * to view the search result.
     *
     * @param string $q
     * @internal param \search $q term
     * @return array of search parameters
     */
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

    /**
    * This beforeSave will set the slug and ensure the proper File request data is being
    * passed to the behavior.
    *
    * @param array $options
    *
    * @return boolean
    */
    public function beforeSave($options = array())
    {
	    if (!empty($this->data['Article']['Media']) && empty($this->data['Media']))
		    $this->data['Media'] = $this->data['Article']['Media'];

        /**
        * Add
        */
        if (!empty($this->data['RelatedData']))
        {
            $this->data['Article']['related_articles'] = json_encode(
                $this->data['RelatedData']
            );
            unset($this->data['RelatedData']);
        }

        if (!empty($this->data['FieldData']))
        {
            foreach($this->data['FieldData'] as $key => $row)
            {
                $this->data['FieldData'][$key] = $this->slug($row);
            }
            
            $this->data['Article']['tags'] = 
                str_replace("'","",json_encode($this->data['FieldData']));
            unset($this->data['FieldData']);
        }

        if (!empty($this->data['Article']['settings']))
        {
            $this->data['Article']['settings'] = json_encode(
                $this->data['Article']['settings']
            );
        }
        
        if (!empty($this->data['Article']['publishing_date']))
        {
            $this->data['Article']['publish_time'] = 
                date("Y-m-d H:i:s", strtotime(
                    $this->data['Article']['publishing_date'] . ' ' .
                    $this->data['Article']['publishing_time']
            ));
            
            if ($this->data['Article']['publish_time'] == date("Y-m-d H:i:")."00" || 
                $this->data['Article']['publish_time'] <= date("Y-m-d H:i:")."00")
            {
                $this->data['Article']['publish_time'] = "0000-00-00 00:00:00";
            }
        }
        
        return true;
    }

    /**
     * @param array $results
     * @return array
     */
    public function convertFieldData($results = array())
    {
        $view = new AdaptcmsView();
        $view->autoRender = false;

        $data_path = VIEW_PATH . 'Elements' . DS . 'FieldTypesData' . DS;

        foreach($results as $key => $result)
        {
            if (!empty($result['ArticleValue']) && is_array($result['ArticleValue']))
            {
                foreach($result['ArticleValue'] as $value)
                {
                    if (!empty($value['Field']) && !empty($value['Field']['field_type_slug']))
                    {
                        $slug = $value['Field']['field_type_slug'];

                        if (file_exists($data_path . $slug . '.ctp'))
                        {
                            $results[$key]['Data'][$value['Field']['title']] = $view->element('FieldTypesData/' . $slug, array('data' => $value));
                        }
                        else
                        {
                            $results[$key]['Data'][$value['Field']['title']] = $view->element('FieldTypesData/default', array('data' => $value));
                        }
                    }
                }
            }
        }

        return $results;
    }

    /**
     * The afterFind is primarily used to automatically decode json for Article data
     *
     * @param mixed $results
     * @param boolean $primary
     *
     * @return array of parsed results
     */
    public function afterFind($results, $primary = false)
    {
	    $role = Configure::read('User.role');

        if (empty($results))
        {
            return false;
        }

        if (!empty($results['id']))
        {
            if (!empty($results['tags']))
            {
                $results['tags'] = json_decode($results['tags'], true);
                $results['tags_list'] = implode(', ', $results['tags']);
            }

            if (!empty($results['settings']))
                $results['settings'] = json_decode($results['settings'], true);
	        
	        if (!empty($results['modified'])) {
		        echo 1;
	        }
        }
        else
        {
            foreach($results as $key => $result)
            {
                if (!empty($result['Article']['tags']))
                {
                    $results[$key]['Article']['tags'] = json_decode($result['Article']['tags'], true);
                    $results[$key]['Article']['tags_list'] = implode(', ', $results[$key]['Article']['tags']);
                }

                if (!empty($result['Article']['settings'])) {
                    $settings = json_decode($result['Article']['settings'], true);
	                $results[$key]['Article']['settings'] = $settings;

	                if (!empty($role) && isset($settings['permissions'][$role]['view']['status']) && $settings['permissions'][$role]['view']['status'] == 0) {
	                    unset($results[$key]);
	                }
                }

	            if (!empty($result['Article']['modified']))
		            $results[$key]['Article']['last_saved'] = $this->getLastSavedDate($result['Article']['modified']);
            }
        }

	    $event = new CakeEvent('Model.Article.afterFind', $this, array('results' => $results, 'model' => $this));
	    $this->getEventManager()->dispatch($event);

	    if (!empty($event->result)) {
			$results = $event->result;
	    }

        return $results;
    }

	/**
	 * After Delete
	 *
	 * @return void
	 */
	public function afterDelete()
    {
        $id = $this->id;
        $related = $this->find('all', array(
            'conditions' => array(
                'Article.related_articles LIKE' => '%"' . $id . '"%'
            )
        ));

        if (!empty($related))
        {
            foreach($related as $article)
            {
                $values = json_decode($article['Article']['related_articles'], true);

                foreach($values as $key => $value)
                {
                    if ($value == $id)
                        unset($values[$key]);
                }

                $this->id = $article['Article']['id'];
                $this->saveField('related_articles', json_encode( array_values($values) ));
            }
        }
    }

	/**
	 * Get Articles
	 *
	 * @param array $results
	 *
	 * @return array
	 */
	public function getArticles($results = array())
	{
		if (!empty($results))
		{
			$articles_list = array_unique(Set::extract('{n}.article_id', $results));
			$find = $this->find('all', array(
				'conditions' => array(
					'Article.id' => $articles_list
				)
			));

			$articles = array();
			foreach($find as $article)
			{
				$articles[$article['Article']['id']] = $article;
			}

			foreach($results as $key => $row)
			{
				$results[$key]['Article'] = $articles[$row['article_id']]['Article'];
			}
		}

		return $results;
	}

	/**
	 * Check Permissions
	 *
	 * @param $user_id
	 * @param $role
	 * @param array $articles
	 * @return array
	 */
	public function checkPermissions($user_id, $role, $articles)
	{
		$actions = array(
			'admin_edit',
			'admin_delete',
			'admin_restore',
			'view'
		);

		if (!empty($articles)) {
			foreach($articles as $key => $article) {
				foreach($actions as $action) {
					$articles[$key]['Permissions'][$action] = $this->Category->hasPermissionAccess($role, $action, $article, $user_id, $article['User']['id']);
	            }
			}
		}

		return $articles;
	}

	/**
	 * Get Last Saved Date
	 *
	 * @param $modified
	 * @return bool|string
	 */
	public function getLastSavedDate($modified)
	{
		$diff = time() - date('U', strtotime($modified));

		if ($diff < 86400) {
			$time = 'g:i A';
		} else {
			$time = 'M jS, Y';
		}

		return date($time, strtotime($modified));
	}

	/**
	 * View Article
	 *
	 * @param CakeRequest $request
	 * @return mixed
	 */
	public function viewArticle($request)
	{
		$data = $request->data;
		$webroot = $request->webroot;
		$id = $data['Article']['id'];

		$data['Comments'] = $this->Comment->find('threaded', array(
			'conditions' => array(
				'Comment.article_id' => $id,
				'Comment.active' => 1
			),
			'contain' => array(
				'User'
			),
			'order' => 'Comment.created DESC'
		));

		if (!empty($data['RelatedArticles']['all'])) {
			$related_articles = $data['RelatedArticles'];
		} else {
			$related_articles = array();
		}

		$data['RelatedArticles'] = $related_articles;

		$data['Fields'] = $this->Category->Field->getFields('Comment');
		$data['Comments'] = $this->Category->Field->getAllModuleData('Comment', $data['Fields'], $data['Comments']);

		$article = $this->getAllRelatedArticles(array($data));
		$data = $article[0];

		if (!isset($data['Media']))
			$data['Media'] = $this->getMedia($id, $webroot);
		
		return $data;
	}

	/**
	 * Get Media
	 *
	 * @param $id
	 * @param $webroot
	 * @param $joins
	 * @return array
	 */
	public function getMedia($id, $webroot, $joins = array())
	{
		if (empty($joins)) {
			$joins = $this->Media->MediaArticle->find('all', array(
				'conditions' => array(
					'MediaArticle.article_id' => $id
				),
				'fields' => array('media_id')
			));
		}

		$media = array();
		if (!empty($joins)) {
			$count = 0;
			$files = array();
			foreach($joins as $join) {
				$find = $this->Media->find('first', array(
					'conditions' => array(
						'Media.id' => (!empty($join['MediaArticle']['media_id']) ? $join['MediaArticle']['media_id'] : $join)
					)
				));

				if (!empty($find)) {
					$result = $this->Media->getLastFileAndCount(array($find));
					$result = $result[0];

					$file_count = (isset($result['File']['count']) ? $result['File']['count'] : 0);
					$count = $count + $file_count;

					$media['all'][$find['Media']['slug']] = array(
						'id' => $find['Media']['id'],
						'slug' => $find['Media']['slug'],
						'title' => $find['Media']['title'],
						'count' => $file_count
					);
					
					if (!empty($result['File']['id'])) {
						$media['all'][$find['Media']['slug']]['file'] = $webroot . $result['File']['dir'] . $result['File']['filename'];
						$media['all'][$find['Media']['slug']]['file_id'] = $result['File']['id'];
						$media['all'][$find['Media']['slug']]['file_label'] = $result['File']['label'];
						$media['all'][$find['Media']['slug']]['file_exists'] = $result['File']['exists'];
						$media['all'][$find['Media']['slug']]['file_caption'] = $result['File']['caption'];
						$media['all'][$find['Media']['slug']]['file_size'] = $result['File']['filesize'];
						$media['all'][$find['Media']['slug']]['file_created'] = $result['File']['created'];

						$files[] = $media['all'][$find['Media']['slug']];
						$media['first'] = $media['all'][$find['Media']['slug']];
					}
				}
			}

			$media['first']['count'] = $count;
			$media['first']['files'] = $files;
		}

		return $media;
	}
}