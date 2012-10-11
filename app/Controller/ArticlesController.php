<?php

class ArticlesController extends AppController {
	public $name = 'Articles';
	public $paginate = array();

	public function beforeFilter()
	{
		parent::beforeFilter();

		if ($this->params->action == "admin_add" || $this->params->action == "admin_edit" ||
			$this->params->action == "admin_index") {
			$this->set('categories', $this->Article->Category->find('list', array(
	            'conditions' => array(
	                'Category.deleted_time' => '0000-00-00 00:00:00'
	            )
	        )));
	    }
	}

	public function admin_index()
	{
		if (!isset($this->params->named['trash'])) {
			$this->paginate = array(
	            'order' => 'Article.created DESC',
	            'contain' => array(
	            	'User' => array(
			            'conditions' => array(
	            			'User.deleted_time' => '0000-00-00 00:00:00'
	        			)
		            ),
	            	'Category' => array(
	        	        'conditions' => array(
	            			'Category.deleted_time' => '0000-00-00 00:00:00'
	            		)
	        	    )
	        	),
	            'limit' => $this->pageLimit,
	            'conditions' => array(
	            	'Article.deleted_time' => '0000-00-00 00:00:00'
	            )
	        );
	    } else {
			$this->paginate = array(
	            'order' => 'Article.created DESC',
	            'contain' => array(
	            	'User' => array(
			            'conditions' => array(
	            			'User.deleted_time' => '0000-00-00 00:00:00'
	        			)
		            ),
	            	'Category' => array(
	        	        'conditions' => array(
	            			'Category.deleted_time' => '0000-00-00 00:00:00'
	            		)
	        	    )
	        	),
	            'limit' => $this->pageLimit,
	            'conditions' => array(
	            	'Article.deleted_time !=' => '0000-00-00 00:00:00'
	            )
	        );	
	    }

        
		$this->request->data = $this->paginate('Article');
	}

	public function admin_add($category_id = null)
	{
		$fields = $this->Article->Category->Field->find('all', array(
			'conditions' => array(
				'Field.category_id' => $category_id
				),
			'order' => array(
				'Field.field_order ASC'
				)
			)
		);
		$this->set('category_id', $category_id);
		$this->set(compact('fields'));
		$this->set('radio_fields', $this->Article->searchArray($fields, "radio"));

        if ($this->request->is('post')) {
        	if (!empty($this->request->data['RelatedData'])) {
        		$this->request->data['Article']['related_articles'] = json_encode($this->request->data['RelatedData']);
        		unset($this->request->data['RelatedData']);
        	}

        	$this->request->data['Article']['slug'] = $this->slug($this->request->data['Article']['title']);
        	$this->request->data['Article']['user_id'] = $this->Auth->user('id');
	        if (!empty($this->request->data['FieldData'])) {
	        	foreach($this->request->data['FieldData'] as $key => $row) {
	        		$this->request->data['FieldData'][$key] = $this->slug($row);
	        	}
	        	
	            $this->request->data['Article']['tags'] = 
	                str_replace("'","",json_encode($this->request->data['FieldData']));
	            unset($this->request->data['FieldData']);
	        }

	        $this->request->data['Article']['publish_time'] = 
	        	date("Y-m-d H:i:s", strtotime(
	        		$this->request->data['Article']['publishing_date'] . ' ' .
	        		$this->request->data['Article']['publishing_time']
	        ));

        	if (isset($this->request->data['ArticleValue'])) {
	        	foreach ($this->request->data['ArticleValue'] as $key => $row) {
	        		if (isset($row['data']) && is_array($row['data'])) {
	        			if (isset($row['data']) && is_array($row['data'])) {
	        				// file upload here
	        				$this->loadModel('File');

	        				$fileUpload = $this->Article->uploadFile(
	        					$row['data'], 
	        					$row['field_id'], 
	        					0, 
	        					"ArticleValue", 
	        					"File"
	        				);
	        				$this->Article->ArticleValue->save($fileUpload['ArticleValue']);
	        				$this->File->save($fileUpload['File']);

	        				unset($this->request->data['ArticleValue'][$key]);
	        			}
	        		} elseif (empty($row['data'])) {
	        			unset($this->request->data['ArticleValue'][$key]);
	        		}
	        	}
        	}
        	if (isset($this->request->data['ArticleFieldData'])) {
        		$fieldData = $this->request->data['ArticleFieldData'];
        		unset($this->request->data['ArticleFieldData']);
        	}

            if ($this->Article->saveAssociated($this->request->data)) {
            	
            	if (isset($fieldData)) {
	        		foreach ($fieldData as $key => $row) {
	        			$this->Article->ArticleValue->create();

						$ArticleValue = array(
							'field_id' => $key,
							'data' => json_encode($row['data']),
							'article_id' => $this->Article->id
							);
						$this->Article->ArticleValue->save($ArticleValue);
					}
	        	}

	        	$this->Article->ArticleValue->updateAll(
	        		array('ArticleValue.article_id' => $this->Article->id),
	        		array('ArticleValue.article_id' => 0)
	        	);

                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Your article has been added.', 'default', array('class' => 'alert alert-success'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to add your article.', 'default', array('class' => 'alert alert-error'));
            }
        } 
	}

	public function admin_edit($id = null)
	{

      	$this->Article->id = $id;

	    if ($this->request->is('get')) {
	        $this->request->data = $this->Article->find('first', array(
	        	'conditions' => array(
	        		'Article.id' => $id
	        	),
	        	'contain' => array(
        			'ArticleValue',
        			'Category'
        			)
	        	)
	        );

	        $this->set('related_articles', $this->Article->getRelatedArticles(
	        	$id, 
	        	$this->request->data['Article']['related_articles']
	        ));

			$fields = $this->Article->Category->Field->find('all', array(
				'conditions' => array(
					'Field.category_id' => $this->request->data['Category']['id']
					),
				'order' => array(
					'Field.field_order ASC'
					)
				)
			);
			$this->set('category_id', $this->request->data['Category']['id']);
			$this->set(compact('fields'));
			$this->set('radio_fields', $this->Article->searchArray($fields, "radio"));
	    } else {
        	$this->request->data['Article']['slug'] = $this->slug($this->request->data['Article']['title']);
        	$this->request->data['Article']['user_id'] = $this->Auth->user('id');
	        if (!empty($this->request->data['FieldData'])) {
	        	foreach($this->request->data['FieldData'] as $key => $row) {
	        		$this->request->data['FieldData'][$key] = $this->slug($row);
	        	}

	            $this->request->data['Article']['tags'] = 
	                str_replace("'","",json_encode($this->request->data['FieldData']));
	            unset($this->request->data['FieldData']);
	        }
	        $this->request->data['Article']['publish_time'] = 
	        	date("Y-m-d H:i:s", strtotime(
	        		$this->request->data['Article']['publishing_date'] . ' ' .
	        		$this->request->data['Article']['publishing_time']
	        ));

	        if ($this->request->data['Article']['publish_time'] == date("Y-m-d H:i:")."00" || 
	        	$this->request->data['Article']['publish_time'] <= date("Y-m-d H:i:")."00") {
	        	$this->request->data['Article']['publish_time'] = "0000-00-00 00:00:00";
	        }

        	if (isset($this->request->data['ArticleValue'])) {
	        	foreach ($this->request->data['ArticleValue'] as $key => $row) {
        			if (isset($row['data']['size']) && $row['data']['size'] > 0 && is_array($row['data'])) {
        				// file upload here
        				$this->loadModel('File');

						$fileUpload = $this->Article->uploadFile(
        					$row['data'], 
        					$row['field_id'], 
        					$id, 
        					"ArticleValue", 
        					"File"
        				);

        				if (!empty($row['filename']) && $row['filename'] == $row['data']['name']) {
        					$file_id = $this->File->findByFilename($row['filename']);
        					$fileUpload['File']['File']['id'] = $file_id['File']['id'];

        					$this->File->save($fileUpload['File']);
        				} elseif (!empty($row['filename'])) {
        					$fileUpload['ArticleValue']['ArticleValue']['id'] = $row['id'];

	        				$this->Article->ArticleValue->save($fileUpload['ArticleValue']);
	        				$this->File->save($fileUpload['File']);        					
        				} else {
	        				$this->Article->ArticleValue->save($fileUpload['ArticleValue']);
	        				$this->File->save($fileUpload['File']);
	        			}

        			} elseif (!empty($row['delete']) && $row['delete'] == 1) {
        				$this->Article->ArticleValue->delete($row['id']);
        			}

        			unset($this->request->data['ArticleValue'][$key]);
	        	}
        	}

        	if (isset($this->request->data['ArticleFieldData'])) {
        		$fieldData = $this->request->data['ArticleFieldData'];
        		unset($this->request->data['ArticleFieldData']);
        	}
        	
            if ($this->Article->saveAssociated($this->request->data)) {
            	if (isset($fieldData)) {
	        		foreach ($fieldData as $key => $row) {
	        			if (!empty($row['id']) && !empty($row['data'])) {
    						$ArticleValue = array(
	        					'ArticleValue' => array(
		        					'id' => $row['id'],
									'field_id' => $key,
									'data' => json_encode($row['data']),
									'article_id' => $this->Article->id
									)
								);

							$this->Article->ArticleValue->save($ArticleValue);
							unset($fieldData[$key]);
						} elseif (!empty($row['data'])) {
							$this->Article->ArticleValue->create();

							$ArticleValue = array(
								'field_id' => $key,
								'data' => json_encode($row['data']),
								'article_id' => $this->Article->id
								);

							$this->Article->ArticleValue->save($ArticleValue);
							unset($fieldData[$key]);
						} elseif(empty($row['data']) && !empty($row['id'])) {
							$this->Article->ArticleValue->delete($row['id']);
						}
					}
	        	}

	            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Your article has been updated.', 'default', array('class' => 'alert alert-success'));
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to update your article.', 'default', array('class' => 'alert alert-error'));
	        }
	    }

	}

	public function admin_delete($id = null, $title = null, $permanent = null)
	{
		if ($this->request->is('post')) {
	        throw new MethodNotAllowedException();
	    }

	    $this->Article->id = $id;

	    if (!empty($permanent)) {
	    	$this->Article->ArticleValue->deleteAll(array('ArticleValue.article_id' => $id));
	    	$delete = $this->Article->delete($id);
	    } else {
	    	$delete = $this->Article->saveField('deleted_time', $this->Article->dateTime());
	    }

	    if ($delete) {
	        $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> The article `'.$title.'` has been deleted.', 'default', array('class' => 'alert alert-success'));
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> The article `'.$title.'` has NOT been deleted.', 'default', array('class' => 'alert alert-error'));
	        $this->redirect(array('action' => 'index'));
	    }
	}

	public function admin_restore($id = null, $title = null)
	{
		if ($this->request->is('post')) {
	        throw new MethodNotAllowedException();
	    }

	    $this->Article->id = $id;

	    if ($this->Article->saveField('deleted_time', '0000-00-00 00:00:00')) {
	        $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> The article `'.$title.'` has been restored.', 'default', array('class' => 'alert alert-success'));
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> The article `'.$title.'` has NOT been restored.', 'default', array('class' => 'alert alert-error'));
	        $this->redirect(array('action' => 'index'));
	    }
	}

	public function admin_ajax_related_search()
	{
    	$this->layout = 'ajax';
    	$this->autoRender = false;

    	if (!empty($this->request->data['id'])) {
    		$id = $this->request->data['id'];
    	} else {
    		$id = '';
    	}

    	if (!empty($this->request->data['category'])) {
    		$conditions = array(
	       		'conditions' => array(
	            	'Article.title LIKE' => '%'.$this->request->data['search'].'%',
	            	'Article.deleted_time' => '0000-00-00 00:00:00',
	            	'Article.category_id' => $this->request->data['category'],
	            	'Article.id !=' => $id
	       		),
				'contain' => array(
					'Category'
				)
			);
    	} else {
    		$conditions = array(
	       		'conditions' => array(
	            	'Article.title LIKE' => '%'.$this->request->data['search'].'%',
	            	'Article.deleted_time' => '0000-00-00 00:00:00',
	            	'Article.id !=' => $id
	       		),
				'contain' => array(
					'Category'
				)
			);
    	}

		$results = $this->Article->find("all", $conditions);

        foreach($results as $result) {            
            $data[] = array(
            	'id' =>$result['Article']['id'],
            	'title' => $result['Article']['title'],
            	'category' => 
            		' ('.$result['Category']['title'].')'
            );
        }

        return json_encode($data);
	}

	public function admin_ajax_related_add()
	{
    	$this->layout = 'ajax';
    	$this->autoRender = false;

	    $this->request->data['Article']['related_articles'] = json_encode($this->request->data['Article']['ids']);

	    if ($this->Article->save($this->request->data)) {
			return '<div id="flashMessageRelated" class="alert alert-success">
					<strong>Success</strong> Related Articles updated.
    			</div>';
    	} else {
			return '<div id="flashMessageRelated" class="alert alert-error">
					<strong>Error</strong> Related Articles could not be updated.
    			</div>';
    	}
	}

	public function view($slug = null)
	{
		$this->request->data = $this->Article->find('first', array(
			'conditions' => array(
				'Article.slug' => $slug,
				'Article.deleted_time' => '0000-00-00 00:00:00'
				),
			'contain' => array(
				'Category',
				'User',
				'ArticleValue' => array(
					'Field'
					)
				)
			)
		);

		if (
			empty($this->request->data['Article']['id']) ||
			$this->request->data['Article']['status'] == 0 ||
			$this->request->data['Article']['publish_time'] > date('Y-m-d H:i:s')) {
				$this->Session->setFlash(
					Configure::read('alert_btn').'
					<strong>Error</strong> Invalid Article', 
					'default', 
					array(
						'class' => 'alert alert-error'
					)
				);
				$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
		}

        $this->set('related_articles', $this->Article->getRelatedArticles(
        	$this->request->data['Article']['id'], 
        	$this->request->data['Article']['related_articles']
        ));

        if (!empty($this->request->data['Category']['slug'])) {
        	$slug = $this->request->data['Category']['slug'];

			if ($this->theme != "Default" && 
				file_exists(VIEW_PATH.'Themed/'.$this->theme.'/Articles/'.$slug.'.ctp') ||\
				file_exists(VIEW_PATH.'Articles/'.$slug.'.ctp')) {
				$this->render(implode('/', array($slug)));
			}
        }
	}

	public function view_by_tag($tag = null)
	{
		$slug = $this->slug($this->params->tag);

		$this->paginate = array(
			'order' => 'Article.created DESC',
			'conditions' => array(
				'Article.tags LIKE' => '%"'.$this->params->tag.'"%',
				'Article.status' => 1,
				'Article.deleted_time' => '0000-00-00 00:00:00'
			),
			'contain' => array(
				'ArticleValue' => array(
					'Field'
				),
				'Category',
				'User'
			),
			'limit' => $limit
		);

        $this->request->data = $this->Article->getAllRelatedArticles(
        	$this->paginate('Article')
        );
	}

	public function rss_index($category = null, $limit = null)
	{
		if (!empty($category)) {
			$cond =  array(
				'Article.status' => 1,
				'Article.deleted_time' => '0000-00-00 00:00:00',
				'Category.slug' => $category
			);			
		} else {
			$cond =  array(
				'Article.status' => 1,
				'Article.deleted_time' => '0000-00-00 00:00:00'
			);
		}

		if (empty($limit)) {
			$limit = 10;
		}

		$this->paginate = array(
			'contain' => array(
				'Category',
				'User',
				'ArticleValue' => array(
					'Field'
				)
			),
			'conditions' => $cond,
			'limit' => $limit,
			'order' => 'Article.created DESC'
		);
    
		$this->request->data = $this->paginate('Article');

		$this->RequestHandler->setContent('application/rss+xml');
	}
}