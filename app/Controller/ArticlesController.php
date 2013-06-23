<?php
App::uses('AppController', 'Controller');

/**
 * Class ArticlesController
 * @property Article $Article
 */
class ArticlesController extends AppController
{
    /**
    * Name of the Controller, 'Articles'
    */
	public $name = 'Articles';

    /**
    * array of permissions for this page
    */
	private $permissions;

	/**
	* Array of helpers, 'Captcha' used for article comments and Fields for convenience methods.
	*/
	public $helpers = array(
		'Captcha',
		'Field'
	);
	
    /**
    * In this beforeFilter we will get the permissions to be used in the view files
    *
    * Additionally, a list of categories is set to the view and for add/edit pages, a list of files
    * for the image field type
    */
	public function beforeFilter()
	{
		parent::beforeFilter();

		$admin_match = array(
			'admin_add',
			'admin_edit',
			'admin_index'
		);

		if (in_array($this->params->action, $admin_match))
		{
            $categories = $this->Article->Category->find('list', array(
                'conditions' => array(
                    'Category.deleted_time' => '0000-00-00 00:00:00'
                )
            ));

            if (empty($categories))
            {
                $this->Session->setFlash('Please add a category in order to manage articles.', 'flash_error');
                $this->redirect(array('action' => 'add', 'controller' => 'categories'));
            }

			$this->set(compact('categories'));
	    }

	    if ($this->params->action == "admin_add" || $this->params->action == "admin_edit")
	    {
			$this->loadModel('File');
			if ($this->params->action == "admin_edit")
			{
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

    /**
    * Returns a paginated index of Articles
    *
    * @return associative array of data
    */
	public function admin_index()
	{
		$conditions = array();

		if (!isset($this->params->named['trash']))
		{
			$conditions['Article.deleted_time'] = '0000-00-00 00:00:00';
	    } else {
			$conditions['Article.deleted_time !='] = '0000-00-00 00:00:00';	
	    }

	    if (!empty($this->params->named['category_id']))
	    {
	    	$conditions['Category.id'] = $this->params->named['category_id'];
	    }

	    if ($this->permissions['any'] == 0)
	    {
	    	$conditions['User.id'] = $this->Auth->user('id');
	    }

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
            'conditions' => $conditions
        );
        
		$this->request->data = $this->Article->Comment->getCommentsCount(
			$this->paginate('Article')
		);
	}

    /**
    * Returns nothing before post
    *
    * On POST, returns error flash or success flash and redirect to index on success
    *
    * @param category_id
    * @return mixed
    */
    public function admin_add($category_id = null)
    {
        $fields = $this->Article->Category->Field->getFields($category_id);

        $this->set(compact('fields', 'category_id'));

        if (!empty($this->request->data))
        {
            $this->request->data['Article']['user_id'] = $this->Auth->user('id');

            if ($this->Article->saveAssociated($this->request->data))
            {
                $this->Session->setFlash('Your article has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your article.', 'flash_error');
            }
        } 
    }

   /**
   * Before POST, sets request data to form
   *
   * After POST, flash error or flash success and redirect to index
   *
   * @param id ID of the database entry, redirect to index if no permissions
   * @return associative array of category data
   */
   public function admin_edit($id = null)
   {
   	    $this->Article->id = $id;

	   if (!empty($this->request->data))
      {
         $this->request->data['Article']['user_id'] = $this->Auth->user('id');

         if ($this->Article->saveAssociated($this->Article->ArticleValue->checkOnEdit($this->request->data)) )
         {
            $this->Session->setFlash('Your article has been updated.', 'flash_success');
            $this->redirect(array('action' => 'index'));
         } else {
            $this->Session->setFlash('Unable to update your article.', 'flash_error');
         }
      }
            
        $this->request->data = $this->Article->find('first', array(
            'conditions' => array(
                'Article.id' => $id
            ),
            'contain' => array(
                'Category',
                'User'
            )
        ));

        if (empty($this->request->data))
        {
            $this->Session->setFlash('Article doesnt exist.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }

        if ($this->request->data['User']['id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }

        $category_id = $this->request->data['Category']['id'];

        $this->set('related_articles', $this->Article->getRelatedArticles(
            $id, 
            $this->request->data['Article']['related_articles']
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

        $fields = $this->Article->Category->Field->getFields($category_id, $id);

        $this->set(compact('fields', 'category_id', 'comments'));
    }

    /**
    * If item has no delete time, then initial deletion is to the trash area (making it in-active on site, if applicable)
    *
    * But if it has a deletion time, meaning it is in the trash, deleting it the second time is permanent.
    *
    * @param id ID of the database entry, redirect to index if no permissions
    * @param title Title of this entry, used for flash message
    * @param permanent If not NULL, this means the item is in the trash so deletion will now be permanent
    * @return redirect
    */
    public function admin_delete($id = null, $title = null, $permanent = null)
    {
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

        if (!empty($permanent))
        {
            $this->Article->ArticleValue->deleteAll(array('ArticleValue.article_id' => $id));
            $delete = $this->Article->delete($id);
        } else {
            $delete = $this->Article->saveField('deleted_time', $this->Article->dateTime() );
        }

        if ($delete)
        {
            $this->Session->setFlash('The article `'.$title.'` has been deleted.', 'flash_success');
        } else {
            $this->Session->setFlash('The article `'.$title.'` has NOT been deleted.', 'flash_error');
        }

        if (!empty($permanent))
        {
            $this->redirect(array('action' => 'index', 'trash' => 1));
        } else {
            $this->redirect(array('action' => 'index'));
        }
    }

    /**
    * Restoring an item will take an item in the trash and reset the delete time
    *
    * This makes it live wherever applicable
    *
    * @param id ID of database entry, redirect if no permissions
    * @param title Title of this entry, used for flash message
    * @return redirect
    */
	public function admin_restore($id = null, $title = null)
	{
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

	    if ($this->Article->saveField('deleted_time', '0000-00-00 00:00:00'))
	    {
	        $this->Session->setFlash('The article `'.$title.'` has been restored.', 'flash_success');
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash('The article `'.$title.'` has NOT been restored.', 'flash_error');
	        $this->redirect(array('action' => 'index'));
	    }
	}

	/**
	* AJAX Admin function that does a search based on a search parameter.
	*
	* If provided, it will also filter by a category and exclude an article.
	*
	* @return json_encode array of data
	*/
	public function admin_ajax_related_search()
	{
    	$this->layout = 'ajax';
    	$this->autoRender = false;

		$conditions = array(
       		'conditions' => array(
            	'Article.title LIKE' => '%'.$this->request->data['search'].'%',
            	'Article.deleted_time' => '0000-00-00 00:00:00'
       		),
			'contain' => array(
				'Category'
			)
		);

    	if (!empty($this->request->data['category']))
    	{
    		$conditions['conditions']['Article.category_id'] = $this->request->data['category'];
    	}

    	if (!empty($this->request->data['id']))
    	{
    		$conditions['conditions']['Article.id !='] = $this->request->data['id'];
    	}

	    if ($this->permissions['any'] == 0)
	    {
	    	$conditions['conditions']['Article.user_id'] = $this->Auth->user('id');
	    }

		$results = $this->Article->find('all', $conditions);

        foreach($results as $result)
        {            
            $data[] = array(
            	'id' =>$result['Article']['id'],
            	'title' => $result['Article']['title'],
            	'category' => 
            		' ('.$result['Category']['title'].')'
            );
        }

        return json_encode($data);
	}

	/**
	* TODO: Move html out, into an element or view
	* AJAX Function that attempts to update the related articles in the admin.
	* An array of ids are parsed to JSON and attempted to be saved.
	*
	* @return string html message on success or error
	*/
	public function admin_ajax_related_add()
	{
    	$this->layout = 'ajax';
    	$this->autoRender = false;

	    $this->request->data['Article']['related_articles'] = json_encode(
	    	$this->request->data['Article']['ids']
	    );

	    if ($this->Article->save($this->request->data))
	    {
			return '<div id="flashMessageRelated" class="alert alert-success">
					<strong>Success</strong> Related Articles updated.
    			</div>';
    	} else {
			return '<div id="flashMessageRelated" class="alert alert-error">
					<strong>Error</strong> Related Articles could not be updated.
    			</div>';
    	}
	}

    /**
     * View action for an article. Permissions are checked, core article data is retrieved, threaded
     * array of comments are retrieved and related comment settings.
     *
     * Related articles are also retrieved, a check is done to make sure the article data is not empty.
     * One last check is to see if a custom template and exists and if so, use it.
     *
     * @param null|string $slug
     * @param int|null $id
     * @internal param string $slug
     * @internal param int $id
     * @return associative array
     */
	public function view($slug = null, $id = null)
	{
      if (empty($slug) && !empty($this->params['slug']))
      {
         $slug = $this->params['slug'];
      }

      if (empty($id) && !empty($this->params['id']))
      {
         $id = $this->params['id'];
      }

        $this->request->data = $this->Article->find('first', array(
            'conditions' => array(
                'Article.slug' => $slug,
                'Article.deleted_time' => '0000-00-00 00:00:00',
                'Article.publish_time <=' => date('Y-m-d H:i:s'),
                'Article.status' => 1
            )
        ));

        if (empty($this->request->data))
        {
            $this->Session->setFlash('Invalid Article', 'flash_error');
            $this->redirect(array(
                'controller' => 'pages',
                'action' => 'display',
                'home'
            ));
        }

        $article = $this->Article->getAllRelatedArticles(array($this->request->data));
        $this->request->data = $article[0];

        if ($this->Auth->user('id') && 
        	$this->request->data['User']['id'] != $this->Auth->user('id') && 
        	$this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect(array('action' => 'index'));	        	
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

		$this->loadModel('SettingValue');

		if (!$this->Auth->user('id'))
		{
    		$captcha = $this->SettingValue->findByTitle('Comment Post Captcha Non-Logged In');

    		if (!empty($captcha['SettingValue']['data']) && $captcha['SettingValue']['data'] == 'Yes')
    		{
    			$this->set('captcha_setting', true);
    		}
    	}

    	$wysiwyg = $this->SettingValue->findByTitle('Comment Post WYSIWYG Editor');

    	if (!empty($wysiwyg['SettingValue']['data']) && $wysiwyg['SettingValue']['data'] == 'Yes')
    	{
    		$this->set(compact('wysiwyg'));
    	}
    	
		if (empty($this->request->data['Article']['id']))
		{
			$this->Session->setFlash('Invalid Article', 'flash_error');
			$this->redirect(array(
				'controller' => 'pages', 
				'action' => 'display', 
				'home'
			));
		}

        if (!empty($this->request->data['RelatedArticles']['all']))
        {
            $related_articles = $this->request->data['RelatedArticles'];
        }
        else
        {
            $related_articles = array();
        }

        $this->set(compact('related_articles'));

        $this->set('article', $this->request->data);

        if (!empty($this->request->data['Category']['slug'])) {
        	$slug = $this->request->data['Category']['slug'];

			if ($this->theme != "Default" && 
				file_exists(VIEW_PATH.'Themed/'.$this->theme.'/Articles/'.$slug.'.ctp') ||
				file_exists(VIEW_PATH.'Articles/'.$slug.'.ctp')) {
				$this->render(implode('/', array($slug)));
			}
        }
	}

    /**
     * Listing of articles by tag
     *
     * @param name $tag
     * @param by|int $limit
     * @internal param \name $tag
     * @internal param \by $limit default lists 10 per page
     * @return associative array of articles
     */
	public function tag($tag, $limit = 10)
	{
		$slug = $this->slug($tag);

		$conditions = array(
			'Article.tags LIKE' => '%"'.$slug.'"%',
			'Article.status' => 1,
            'Article.publish_time <=' => date('Y-m-d H:i:s'),
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
        $this->set('tag', $slug);
	}

    /**
     * Not fully functional, renders rss file but headers are not proper XML.
     *
     * @param filter|null $category
     * @param amount|int $limit
     * @internal param \filter $category by category, optional
     * @internal param \amount $limit to list on rss, 10 by default
     * @return associative array of articles
     */
	public function rss_index($category = null, $limit = 10)
	{
            $cond =  array(
                    'Article.status' => 1,
                    'Article.publish_time <=' => date('Y-m-d H:i:s'),
                    'Article.deleted_time' => '0000-00-00 00:00:00'
            );
            
            if (!empty($category)) 
            {
                $cond['Category.slug'] = $category;
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
                    // echo 1;
            }

            $this->request->data = $this->paginate('Article');
            Configure::write('debug', 0);
            $this->layout = 'rss/default';
            $this->RequestHandler->setContent('rss', 'application/rss+xml');
	}

	/**
	* Experimental function, useable for a REST interface
	*
	* @return json_encode array of article data
	*/
	public function index()
	{
		$data = $this->Article->find('all', array(
			'conditions' => array(
				'Article.deleted_time' => '0000-00-00 00:00:00',
                                'Article.publish_time <=' => date('Y-m-d H:i:s'),
                                'Article.status' => 1
			),
			'contain' => array(
				'User',
				'Category'
			),
			'fields' => array(
				'id', 'title', 'slug', 'tags', 'User.username', 'Category.title', 'status', 'publish_time', 'created'
			)
		));

		foreach($data as $key => $row)
		{
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