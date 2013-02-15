<?php

class PagesController extends AppController 
{
	public $name = 'Pages';
	private $permissions;
	public $components = array(
		'Api'
	);
	public $helpers = array(
		'Field'
	);

	public function beforeFilter()
	{
		parent::beforeFilter();

		$this->permissions = $this->getPermissions();
	}

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

	public function admin_add()
	{
        if ($this->request->is('post')) {
    		$this->request->data['Page']['slug'] = $this->slug($this->request->data['Page']['title']);
    		$this->request->data['Page']['user_id'] = $this->Auth->user('id');

        	$fh = fopen(VIEW_PATH."Pages/".$this->request->data['Page']['slug'].".ctp", 'w') or die("can't open file");
			fwrite($fh, $this->request->data['Page']['content']);
			fclose($fh);

            if ($this->Page->save($this->request->data)) {
                $this->Session->setFlash('Your page has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your page.', 'flash_error');
            }
        } 
	}

	public function admin_edit($id = null)
	{

      	$this->Page->id = $id;

	    if (!empty($this->request->data))
	    {
	    	$this->request->data['Page']['slug'] = $this->slug($this->request->data['Page']['title']);
	    	$this->request->data['Page']['user_id'] = $this->Auth->user('id');
        	
        	$fh = fopen(VIEW_PATH."Pages/".$this->request->data['Page']['slug'].".ctp", 'w') or die("can't open file");
			fwrite($fh, $this->request->data['Page']['content']);
			fclose($fh);

			if ($this->request->data['Page']['title'] != $this->request->data['Page']['old_title']) {
				unlink(VIEW_PATH."Pages/".$this->slug($this->request->data['Page']['old_title']).".ctp");
			}

	        if ($this->Page->save($this->request->data)) {
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

	public function admin_delete($id = null, $title = null, $permanent = null)
	{

		if ($this->request->is('post')) {
	        throw new MethodNotAllowedException();
	    }

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
		    if (is_readable(VIEW_PATH.'Pages/'.$this->slug($title).'.ctp')) {
		    	unlink(VIEW_PATH.'Pages/'.$this->slug($title).'.ctp');
		    }
        } else {
            $delete = $this->Page->saveField('deleted_time', $this->Page->dateTime());
        }

	    if ($delete) {
	        $this->Session->setFlash('The page `'.$title.'` has been deleted.', 'flash_success');
	    } else {
	    	$this->Session->setFlash('The page `'.$title.'` has NOT been deleted.', 'flash_error');
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

	public function display() {
		$path = func_get_args();

		/*
		$this->GoogleAnalytics->authenticate(
			array(
				'email' => 'charliepage88@gmail.com',
				'password' => '7!kZ98mF$s90aD',
				'profileId' => '56502081'
			)
		);
		$this->GoogleAnalytics->setDateRange( date("Y-m-d", strtotime('-1 year')), date("Y-m-d") );
		$chart = $this->GoogleAnalytics->getReport(
	      array(
	        'dimensions' => urlencode('ga:month'),
	        'metrics' => urlencode('ga:visits')
	      )
	    );
	    debug($chart);
		*/

		if ($path[0] == 'home') {
			$this->loadModel('Article');
			$this->loadModel('SettingValue');

			$setting1 = $this->SettingValue->findByTitle('Number of Articles on Homepage');
			$setting2 = $this->SettingValue->findByTitle('Categories of Articles to show on homepage');

			if (empty($setting1)) {
				$setting1['SettingValue']['data'] = 5;
			}

			if (!empty($setting2)) {
				$categories = array_map('strtolower',
					array_map('trim', 
						explode(
							",",
							$setting2['SettingValue']['data']
						)
					)
				);
			} else {
				$this->loadModel('Category');

				$category = $this->Category->find('first');
				$categories = $category['Category']['slug'];
			}

			$conditions = array(
				'Article.status' => 1,
				'Article.deleted_time' => '0000-00-00 00:00:00',
				'Category.slug' => $categories
			);

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
						'Field'
					)
				),
				'limit' => $setting1['SettingValue']['data'],
				'conditions' => $conditions,
				'order' => 'Article.created DESC'
			);

	        $this->request->data = $this->Article->getAllRelatedArticles(
	        	$this->paginate('Article')
	        );

	        $this->set('article', $this->request->data);
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
			$this->request->data = $this->Page->findBySlug($path[0]);

			if (!empty($this->request->data)) {
				$this->set('title_for_layout', $this->request->data['Page']['title']);
			}

			$this->render(implode('/', $path));
		}
	}

	public function admin()
	{
		$this->set('news', $this->Api->getSiteArticles(1, 'news'));
		$this->set('blog', $this->Api->getSiteArticles(1, 'blog'));
		$this->set('newest_plugin', $this->Api->getPlugins(1, 'created', 'desc'));
		$this->set('newest_theme', $this->Api->getThemes(1, 'created', 'desc'));
	}	
}