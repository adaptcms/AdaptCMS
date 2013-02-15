<?php
App::import('Vendor', 'ayah');
App::import('Vendor', 'ayah_config');

class ArticlesController extends AppController {
	public $name = 'Articles';
	public $paginate = array();
	private $permissions;
	public $helpers = array(
		'Captcha',
		'Field'
	);
	
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

	    if ($this->params->action == "admin_add" || $this->params->action == "admin_edit") {
			$this->loadModel('File');
			if ($this->params->action == "admin_edit") {
				$images = $this->File->find('all', array(
					'conditions' => array(
						'File.deleted_time' => '0000-00-00 00:00:00',
						'File.mimetype LIKE' => '%image%'
					)
				));
			} else {
				$this->paginate = array(
					'conditions' => array(
						'File.deleted_time' => '0000-00-00 00:00:00',
						'File.mimetype LIKE' => '%image%'
					),
					'limit' => 9
				);

				$images = $this->paginate('File');
			}
			$image_path = WWW_ROOT;

			$this->set(compact('images', 'image_path'));
		}

		$this->permissions = $this->getPermissions();
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

	    if (!empty($this->params->named['category_id']))
	    {
	    	$this->paginate['conditions']['Category.id'] = $this->params->named['category_id'];
	    }

	    if ($this->permissions['any'] == 0)
	    {
	    	$this->paginate['conditions']['User.id'] = $this->Auth->user('id');
	    }
        
		$this->request->data = $this->paginate('Article');
		
		foreach($this->request->data as $key => $row) {
			$this->request->data[$key]['Comment']['count'] = $this->Article->Comment->find('count', array(
				'conditions' => array(
					'Comment.article_id' => $row['Article']['id']
				)
			));
		}
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
		));
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

	        if (!empty($this->request->data['Article']['settings'])) {
	        	$this->request->data['Article']['settings'] = json_encode($this->request->data['Article']['settings']);
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

                $this->Session->setFlash('Your article has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your article.', 'flash_error');
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
        			'ArticleValue' => array(
        				'File'
        			),
        			'Category',
        			'User'
	        	)
	        ));

	        if ($this->request->data['User']['id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
	        {
                $this->Session->setFlash('You cannot access another users item.', 'flash_error');
                $this->redirect(array('action' => 'index'));	        	
	        }

	        if (!empty($this->request->data['Article']['settings']))
	        {
	        	$this->request->data['Article']['settings'] = json_decode($this->request->data['Article']['settings']);
	        }
	        
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
			));

			$this->paginate = array(
				'conditions' => array(
					'Comment.article_id' => $id
				),
				'contain' => array(
					'User'
				)
			);
			$comments = $this->paginate('Comment');

			$this->set('category_id', $this->request->data['Category']['id']);
			$this->set(compact('fields', 'comments'));
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

	        if (!empty($this->request->data['Article']['settings'])) {
	        	$this->request->data['Article']['settings'] = json_encode($this->request->data['Article']['settings']);
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

	        			unset($this->request->data['ArticleValue'][$key]);

        			} elseif (!empty($row['delete']) && $row['delete'] == 1) {
        				$this->Article->ArticleValue->delete($row['id']);
        				unset($this->request->data['ArticleValue'][$key]);
        			} elseif (empty($row['id']) && empty($row['data'])) {
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

	            $this->Session->setFlash('Your article has been updated.', 'flash_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your article.', 'flash_error');
	        }
	    }

	}

	public function admin_delete($id = null, $title = null, $permanent = null)
	{
		if ($this->request->is('post')) {
	        throw new MethodNotAllowedException();
	    }

	    $this->Article->id = $id;

        $data = $this->Article->find('first', array(
        	'conditions' => array(
        		'Article.id' => $id
        	),
        	'contain' => array(
    			'User'
        	)
        ));
        if ($data['User']['id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect(array('action' => 'index'));	        	
        }

	    if (!empty($permanent)) {
	    	$this->Article->ArticleValue->deleteAll(array('ArticleValue.article_id' => $id));
	    	$delete = $this->Article->delete($id);
	    } else {
	    	$delete = $this->Article->saveField('deleted_time', $this->Article->dateTime());
	    }

	    if ($delete) {
	        $this->Session->setFlash('The article `'.$title.'` has been deleted.', 'flash_success');
	    } else {
	    	$this->Session->setFlash('The article `'.$title.'` has NOT been deleted.', 'flash_error');
	    }

	    if (!empty($permanent)) {
	    	$this->redirect(array('action' => 'index', 'trash' => 1));
	    } else {
	    	$this->redirect(array('action' => 'index'));
	    }
	}

	public function admin_restore($id = null, $title = null)
	{
		if ($this->request->is('post')) {
	        throw new MethodNotAllowedException();
	    }

	    $this->Article->id = $id;

        $data = $this->Article->find('first', array(
        	'conditions' => array(
        		'Article.id' => $id
        	),
        	'contain' => array(
    			'User'
        	)
        ));
        if ($data['User']['id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect(array('action' => 'index'));	        	
        }

	    if ($this->Article->saveField('deleted_time', '0000-00-00 00:00:00')) {
	        $this->Session->setFlash('The article `'.$title.'` has been restored.', 'flash_success');
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash('The article `'.$title.'` has NOT been restored.', 'flash_error');
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

	    if ($this->permissions['any'] == 0)
	    {
	    	$conditions['conditions']['Article.user_id'] = $this->Auth->user('id');
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

	public function view($slug)
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
					'Field',
					'File'
				)
			)
		));

        if ($this->Auth->user('id') && 
        	$this->request->data['User']['id'] != $this->Auth->user('id') && 
        	$this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect(array('action' => 'index'));	        	
        }

        if (!empty($this->request->data['Article']['settings']))
	    {
			$this->request->data['Article']['settings'] = json_decode($this->request->data['Article']['settings']);
		}

		$this->request->data['Comments'] = $this->Article->Comment->find('threaded', array(
			'conditions' => array(
				'Comment.article_id' => $this->request->data['Article']['id'],
				'Comment.active' => 1
			),
			'contain' => array(
				'User'
			)
		));

		if (!$this->Auth->user('id')) {
			$this->loadModel('SettingValue');
    		$captcha = $this->SettingValue->findByTitle('Comment Post Captcha Non-Logged In');

    		if (!empty($captcha['SettingValue']['data']) && $captcha['SettingValue']['data'] == 'Yes') {
    			$this->set('captcha_setting', true);
    		}
    	}

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

        $this->set('article', $this->request->data);

        if (!empty($this->request->data['Category']['slug'])) {
        	$slug = $this->request->data['Category']['slug'];

			if ($this->theme != "Default" && 
				file_exists(VIEW_PATH.'Themed/'.$this->theme.'/Articles/'.$slug.'.ctp') ||\
				file_exists(VIEW_PATH.'Articles/'.$slug.'.ctp')) {
				$this->render(implode('/', array($slug)));
			}
        }
	}

	public function tag($tag)
	{
		$slug = $this->slug($tag);

		if (empty($limit)) {
			$limit = 10;
		}

		$conditions = array(
			'Article.tags LIKE' => '%"'.$slug.'"%',
			'Article.status' => 1,
			'Article.deleted_time' => '0000-00-00 00:00:00'
		);

	    if ($this->permissions['any'] == 0)
	    {
	    	$conditions['User.id'] = $this->Auth->user('id');
	    }

		$this->paginate = array(
			'order' => 'Article.created DESC',
			'conditions' => $conditions,
			'contain' => array(
				'ArticleValue' => array(
					'Field',
					'File'
				),
				'Category',
				'User'
			),
			'limit' => $limit
		);

        $this->request->data = $this->Article->getAllRelatedArticles(
        	$this->paginate('Article')
        );

        $this->set('article', $this->request->data);
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

		if ($this->RequestHandler->isRss() ) {
			echo 1;
		}
    
		$this->request->data = $this->paginate('Article');
		Configure::write('debug', 0);
		$this->layout = 'rss/default';
		$this->RequestHandler->setContent('rss', 'application/rss+xml');
	}

	public function index()
	{
		$data = $this->Article->find('all', array(
			'conditions' => array(
				'Article.deleted_time' => '0000-00-00 00:00:00'
			),
			'contain' => array(
				'User',
				'Category'
			),
			'fields' => array(
				'id', 'title', 'slug', 'tags', 'User.username', 'Category.title', 'status', 'publish_time', 'created'
			)
		));

		foreach($data as $key => $row) {
			$data[$key] = $row['Article'];
			$data[$key]['category'] = $row['Category']['title'];
			$data[$key]['user'] = $row['User']['username'];
			unset($data[$key]['Category'], $data[$key]['User']);
		}

		$this->layout = '';
		$this->autoRender = false;

		echo json_encode($data);
	}
}