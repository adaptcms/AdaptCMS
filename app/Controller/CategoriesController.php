<?php

class CategoriesController extends AppController
{
    /**
    * Name of the Controller, 'Categories'
    */
	public $name = 'Categories';

    /**
    * array of permissions for this page
    */
	private $permissions;

    /**
    * In this beforeFilter we will get the permissions to be used in the view files
    */
	public function beforeFilter()
	{
		parent::beforeFilter();

		$this->permissions = $this->getPermissions();
	}

    /**
    * Returns a paginated index of Categories
    *
    * @return associative array of categories data
    */
	public function admin_index()
	{
		$conditions = array();

	    if ($this->permissions['any'] == 0)
	    {
	    	$conditions['User.id'] = $this->Auth->user('id');
	    }

		if (!isset($this->params->named['trash'])) {
			$conditions['Category.deleted_time'] = '0000-00-00 00:00:00';
		} else {
			$conditions['Category.deleted_time !='] = '0000-00-00 00:00:00';
        }

		$this->paginate = array(
            'order' => 'Category.created DESC',
            'limit' => $this->pageLimit,
            'conditions' => $conditions,
            'contain' => array(
            	'User'
            )
        );
        
		$this->request->data = $this->paginate('Category');
	}

    /**
    * Returns nothing before post
    *
    * On POST, returns error flash or success flash and redirect to index on success
    *
    * @return mixed
    */
	public function admin_add()
	{
        if (!empty($this->request->data))
        {
        	$this->Category->create();

    		$this->request->data['Category']['user_id'] = $this->Auth->user('id');

            if ($this->Category->save($this->request->data))
            {
                $this->Session->setFlash('Your category has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your category.', 'flash_error');
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
      	$this->Category->id = $id;

      	$this->paginate = array(
      		'conditions' => array(
      			'Article.deleted_time' => '0000-00-00 00:00:00',
      			'Article.category_id' => $id
      		),
      		'contain' => array(
      			'User'
      		),
      		'limit' => 7
      	);

      	$articles = $this->paginate('Article');

      	$fields = $this->Category->Field->find('all', array(
      		'conditions' => array(
      			'Field.deleted_time' => '0000-00-00 00:00:00',
      			'Field.category_id' => $id
      		)
      	));

      	$this->set(compact('fields', 'articles'));

	    if (!empty($this->request->data))
	    {
	    	$this->request->data['Category']['user_id'] = $this->Auth->user('id');

	        if ($this->Category->save($this->request->data))
	        {
	            $this->Session->setFlash('Your category has been updated.', 'flash_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your category.', 'flash_error');
	        }
	    }

        $this->request->data = $this->Category->find('first', array(
        	'conditions' => array(
        		'Category.id' => $id
        	),
        	'contain' => array(
        		'User'
        	)
        ));

        if ($this->request->data['User']['id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect(array('action' => 'index'));	        	
        }
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
	    $this->Category->id = $id;

        $data = $this->Category->find('first', array(
        	'conditions' => array(
        		'Category.id' => $id
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
	    	$delete = $this->Category->delete($id);

	    	$article_path = VIEW_PATH . 'Articles' . DS . $this->slug($title) . '.ctp';
	    	$categories_path = VIEW_PATH . 'Categories' . DS . $this->slug($title) . '.ctp';

	    	if (file_exists($article_path))
	    	{
	    		unlink($article_path);
	    	}

	    	if (file_exists($categories_path))
	    	{
	    		unlink($categories_path);
	    	}
	    } else {
	    	$delete = $this->Category->saveField('deleted_time', $this->Category->dateTime());
	    }

	    if ($delete)
	    {
	        $this->Session->setFlash('The category `'.$title.'` has been deleted.', 'flash_success');
	    } else {
	    	$this->Session->setFlash('The category `'.$title.'` has NOT been deleted.', 'flash_error');
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
	    $this->Category->id = $id;

        $data = $this->Category->find('first', array(
        	'conditions' => array(
        		'Category.id' => $id
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

	    if ($this->Category->saveField('deleted_time', '0000-00-00 00:00:00'))
	    {
	        $this->Session->setFlash('The category `'.$title.'` has been restored.', 'flash_success');
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash('The category `'.$title.'` has NOT been restored.', 'flash_error');
	        $this->redirect(array('action' => 'index'));
	    }
	}

	/**
	* Looks up how many articles to list on category page, gets all articles
	* belonging to specified category and uses either the default template, or, 
	* if it exists, a custom template.
	* 
	* @param slug Slug of the Category
	* @return article Associative Array of article data
	* @return title_for_layout Page Title
	*/
	public function view($slug)
	{
        $category = $this->Category->findBySlug($slug);

        if (empty($category))
        {
            $this->Session->setFlash('Invalid Category', 'flash_error');
            $this->redirect(array(
                'controller' => 'pages',
                'action' => 'display',
                'home'
            ));
        }

		$this->loadModel('SettingValue');
		
		if ($limit = $this->SettingValue->findByTitle('Number of Articles to list on Category Page'))
		{
			$limit = $limit['SettingValue']['data'];
		} else {
			$limit = 10;
		}

		$conditions = array(
			'Article.category_id' => $category['Category']['id'],
			'Article.status' => 1,
            'Article.publish_time <=' => date('Y-m-d H:i:s'),
			'Article.deleted_time' => '0000-00-00 00:00:00'
		);

	    if ($this->permissions['any'] == 0 && $this->Auth->user('id'))
	    {
	    	$conditions['User.id'] = $this->Auth->user('id');
	    }

		$this->paginate = array(
			'order' => 'Article.created DESC',
			'conditions' => $conditions,
			'limit' => $limit
		);

		$articles = $this->paginate('Article');

		$this->request->data = $this->Category->Article->getAllRelatedArticles(
			$articles
		);

		$this->set('category', $category['Category']);
		$this->set('article', $this->request->data);
		$this->set('title_for_layout', ucfirst($slug));

		if ($this->theme != "Default" && 
			file_exists(VIEW_PATH.'Themed/'.$this->theme.'/Categories/'.$slug.'.ctp') ||
			file_exists(VIEW_PATH.'Categories/'.$slug.'.ctp'))
		{
			$this->render(implode('/', array($slug)));
		}
	}

	/**
	* Returns a json_encoded array of category article data
	*
	* This is temporary.
	*
	* @return json_encode Array of related Article Data
	*/
	public function index()
	{
		$data = $this->Category->find('all', array(
			'conditions' => array(
				'Category.deleted_time' => '0000-00-00 00:00:00'
			),
			'fields' => array(
				'id', 'title', 'slug', 'created'
			)
		));

		$this->layout = '';
		$this->autoRender = false;

		echo json_encode($data);
	}
}