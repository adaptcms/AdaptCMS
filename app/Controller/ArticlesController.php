<?php

class ArticlesController extends AppController {
	public $name = 'Articles';

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

		$this->set('categories', $this->Article->Category->find('list', array(
            'conditions' => array(
                'Category.deleted_time' => '0000-00-00 00:00:00'
            )
        )));
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
			$this->request->data['Article']['publish_time'] >= date('Y-m-d H:i:s')) {
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
	}

	public function view_by_tag($tag = null)
	{
		$slug = $this->slug($this->params->tag);

		$this->request->data = $this->Article->find("all", array(
			'conditions' => array(
				'Article.tags LIKE' => '%"'.$this->params->tag.'"%'
				),
			'contain' => array(
				'ArticleValue' => array(
					'Field'
				),
				'Category',
				'User'
			)
		));
	}
}