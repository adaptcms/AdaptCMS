<?php

class PagesController extends AppController 
{
    /**
    * Name of the Controller, 'Pages'
    */
	public $name = 'Pages';

    /**
    * array of permissions for this page
    */
	private $permissions;

	/**
	* The AdaptCMS Field Helper
	*/
	public $helpers = array(
		'Field'
	);

    /**
    * In this beforeFilter we will get the permissions to be used in the view files
    */
	public function beforeFilter()
	{
		parent::beforeFilter();

		$this->permissions = $this->getPermissions();
	}

    /**
    * Returns a paginated index of Pages
    *
    * @return associative array of pages data
    */
	public function admin_index()
	{
		$conditions = array();

		if (!isset($this->params->named['trash'])) {
			$conditions['Page.deleted_time'] = '0000-00-00 00:00:00';
	    } else {
	    	$conditions['Page.deleted_time !='] = '0000-00-00 00:00:00';
        }

	    if ($this->permissions['any'] == 0)
	    {
	    	$conditions['User.id'] = $this->Auth->user('id');
	    }

        $this->paginate = array(
            'order' => 'Page.created DESC',
            'limit' => $this->pageLimit,
            'conditions' => $conditions,
            'contain' => array(
            	'User'
            )
        );

        $this->request->data = $this->paginate('Page');
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
    		$this->request->data['Page']['user_id'] = $this->Auth->user('id');

            if ($this->Page->save($this->request->data))
            {
                $this->Session->setFlash('Your page has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your page.', 'flash_error');
            }
        } 
	}

    /**
    * Before POST, sets request data to form
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param id ID of the database entry, redirect to index if no permissions
    * @return associative array of page data
    */
	public function admin_edit($id = null)
	{
      	$this->Page->id = $id;

	    if (!empty($this->request->data))
	    {
	    	$this->request->data['Page']['user_id'] = $this->Auth->user('id');

	        if ($this->Page->save($this->request->data))
	        {
	            $this->Session->setFlash('Your page has been updated.', 'flash_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your page.', 'flash_error');
	        }
	    }

        $this->request->data = $this->Page->find('first', array(
        	'conditions' => array(
        		'Page.id' => $id
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
	    $this->Page->id = $id;

        $data = $this->Page->find('first', array(
        	'conditions' => array(
        		'Page.id' => $id
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
            $delete = $this->Page->delete($id);

		    if (is_readable(VIEW_PATH.'Pages/' . $this->slug($title) . '.ctp'))
		    {
		    	unlink(VIEW_PATH.'Pages/' . $this->slug($title) . '.ctp');
		    }
        } else {
            $delete = $this->Page->saveField('deleted_time', $this->Page->dateTime());
        }

	    if ($delete)
	    {
	        $this->Session->setFlash('The page `'.$title.'` has been deleted.', 'flash_success');
	    } else {
	    	$this->Session->setFlash('The page `'.$title.'` has NOT been deleted.', 'flash_error');
	    }

	    if (!empty($permanent))
	    {
	    	$count = $this->Page->find('count', array(
	    		'conditions' => array(
	    			'Page.deleted_time !=' => '0000-00-00 00:00:00'
	    		)
	    	));

	    	$params = array('action' => 'index');

	    	if ($count > 0)
	    	{
	    		$params['trash'] = 1;
	    	}

	    	$this->redirect($params);
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
        $this->Page->id = $id;

        $data = $this->Page->find('first', array(
        	'conditions' => array(
        		'Page.id' => $id
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

        if ($this->Page->saveField('deleted_time', '0000-00-00 00:00:00')) {
            $this->Session->setFlash('The page `'.$title.'` has been restored.', 'flash_success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The page `'.$title.'` has NOT been restored.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }
    }

    /**
    * If page is homepage, will get latest articles - otherwise will return page content.
    *
    * If requested page does not exist, redirect to homepage.
    *
    * @return associative array or redirect
    */
	public function display()
	{
		$path = func_get_args();

		if ($path[0] == 'home') {
			$this->loadModel('Article');
			$this->loadModel('SettingValue');

			$settings = $this->SettingValue->find('all', array(
				'conditions' => array(
					'OR' => array(
						array('SettingValue.title' => 'Number of Articles on Homepage'),
						array('SettingValue.title' => 'Categories of Articles to show on homepage')
					)
				)
			));

			if (empty($settings[0]))
			{
				$limit = 5;
			}
			else
			{
				$limit = $settings[0]['SettingValue']['data'];
			}

			if (!empty($settings[1]))
			{
				$categories = array_map('strtolower',
					array_map('trim', 
						explode(
							",",
							$settings[1]['SettingValue']['data']
						)
					)
				);
			}

            $conditions = array(
                'Article.status' => 1,
                'Article.publish_time <=' => date('Y-m-d H:i:s'),
                'Article.deleted_time' => '0000-00-00 00:00:00'
            );

            if (!empty($categories))
                $conditions['Category.slug'] = $categories;

			$permissions = $this->getRelatedPermissions($this->permissionLookup(array('show' => true)));

		    if ($permissions['related']['articles']['view']['any'] == 0 && $this->Auth->user('id'))
		    {
		    	$conditions['User.id'] = $this->Auth->user('id');
		    }

			$this->paginate = array(
				'contain' => array(
					'Category',
					'User',
					'ArticleValue' => array(
						'Field',
						'File'
					)
				),
				'limit' => $limit,
				'conditions' => $conditions,
				'order' => 'Article.created DESC'
			);

	        $this->request->data = $this->Article->getAllRelatedArticles(
	        	$this->paginate('Article')
	        );

	        $this->set('articles', $this->request->data);
		}

		if ($path[0] == 'home' or $path[0] == 'denied') {
			$count = count($path);
			if (!$count) {
				$this->redirect('/');
			}
			$page = $subpage = $title_for_layout = null;

			if (!empty($path[0])) {
				$page = $path[0];
			}
			if (!empty($path[1])) {
				$subpage = $path[1];
			}
			if (!empty($path[$count - 1])) {
				$title_for_layout = Inflector::humanize($path[$count - 1]);
			}
			$this->set(compact('page', 'subpage', 'title_for_layout'));
			$this->render(implode('/', $path));
		} else {
			$this->request->data = $this->Page->find('first', array(
				'conditions' => array(
					'Page.slug' => $path[0],
					'Page.deleted_time' => '0000-00-00 00:00:00'
				)
			));

			if (!empty($this->request->data)) {
				$this->set('title_for_layout', $this->request->data['Page']['title']);
			} else {
	            $this->Session->setFlash('This page doesnt exist.', 'flash_error');
	            $this->redirect('/');
			}

			$this->render(implode('/', $path));
		}
	}

    /**
    * This is the main admin page located at yoursite.com/admin
    *
	* Currently API calls are made to get the latest AdaptCMS.com news/blog and newest plugin/theme.
	*
	* The default AdaptCMS install has two dynamic blocks setup, pulling in the newest 5 users and articles.
    *
    * @return associative array of news, blog, newest_plugin and newest_theme
    */
	public function admin()
	{
		/*
		* API Component is used to connect to the adaptcms.com website
		*/
		$this->Api = $this->Components->load('Api');

		$this->set('news', $this->Api->getSiteArticles(1, 'news'));
		$this->set('blog', $this->Api->getSiteArticles(1, 'blog'));
		$this->set('newest_plugin', $this->Api->getPlugins(1, 'created', 'desc'));
		$this->set('newest_theme', $this->Api->getThemes(1, 'created', 'desc'));
	}	
}