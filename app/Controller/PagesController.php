<?php
App::uses('AppController', 'Controller');
/**
 * Class PagesController
 * @property SettingValue $SettingValue
 * @property Page $Page
 * @property CmsApiComponent $CmsApi
 */
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

    public $cacheAction = array(
        'display' => '1 day'
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
    * @return array of pages data
    */
	public function admin_index()
	{
		$conditions = array();

		if (isset($this->request->named['trash']))
			$conditions['Page.only_deleted'] = true;

	    if ($this->permissions['any'] == 0)
	    	$conditions['User.id'] = $this->Auth->user('id');

        $this->Paginator->settings = array(
            'conditions' => $conditions,
            'contain' => array(
            	'User'
            )
        );

        $this->request->data = $this->Paginator->paginate('Page');
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
                $this->Session->setFlash('Your page has been added.', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your page.', 'error');
            }
        }

        $path = VIEW_PATH . 'Pages/*.ctp';
        $docs = $this->Page->getDocs($path);

        $this->set(compact('docs'));
	}

    /**
    * Before POST, sets request data to form
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param integer $id of the database entry, redirect to index if no permissions
    * @return mixed
    */
	public function admin_edit($id)
	{
        if (empty($id))
            return $this->redirect(array('action' => 'index'));

      	$this->Page->id = $id;

	    if (!empty($this->request->data))
	    {
	    	$this->request->data['Page']['user_id'] = $this->Auth->user('id');

	        if ($this->Page->save($this->request->data))
	        {
	            $this->Session->setFlash('Your page has been updated.', 'success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your page.', 'error');
	        }
	    }

        $this->request->data = $this->Page->findById($id);
		$this->hasAccessToItem($this->request->data);

        $path = $this->Page->_getPath($this->request->data['Page']['slug']);
        if (is_writable($path))
        {
            $writable = 1;
            $this->request->data['Page']['content'] = $this->Page->getContent($this->request->data['Page']['slug']);
        }
        else
        {
            $writable = $path;
        }

        $docs = $this->Page->getDocs($path);

        $this->set(compact('writable', 'docs'));
	}

    /**
     * If item has no delete time, then initial deletion is to the trash area (making it in-active on site, if applicable)
     *
     * But if it has a deletion time, meaning it is in the trash, deleting it the second time is permanent.
     *
     * @param integer $id id of the database entry, redirect to index if no permissions
     * @param string $title Title of this entry, used for flash message
     * @return mixed
     */
	public function admin_delete($id, $title = null)
	{
	    $this->Page->id = $id;

        $data = $this->Page->findById($id);
		$this->hasAccessToItem($data);

		$permanent = $this->Page->remove($data);

		$this->Session->setFlash('The page `'.$title.'` has been deleted.', 'success');

		if ($permanent)
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
    * @param integer $id ID of database entry, redirect if no permissions
    * @param string $title Title of this entry, used for flash message
    * @return void
    */
    public function admin_restore($id, $title = null)
    {
        $this->Page->id = $id;

        $data = $this->Page->findById($id);
	    $this->hasAccessToItem($data);

        if ($this->Page->restore()) {
            $this->Session->setFlash('The page `'.$title.'` has been restored.', 'success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The page `'.$title.'` has NOT been restored.', 'error');
            $this->redirect(array('action' => 'index'));
        }
    }

    /**
    * If page is homepage, will get latest articles - otherwise will return page content.
    *
    * If requested page does not exist, redirect to homepage.
    *
    * @return void
    */
	public function display()
	{
		$path = func_get_args();

        if (empty($path[0]))
            return $this->redirect('/');

		if ($path[0] == 'home')
        {
			$this->loadModel('Article');
			$this->loadModel('SettingValue');

			$num_articles = $this->SettingValue->findByTitle('Number of Articles on Homepage');
			$categories_home = $this->SettingValue->findByTitle('Categories of Articles to show on homepage');

			if (empty($num_articles))
			{
				$limit = 5;
			}
			else
			{
				$limit = $num_articles['SettingValue']['data'];
			}

			if (!empty($categories_home['SettingValue']['data']))
			{
                $categories = $this->Article->Category->find('all', array(
                    'conditions' => array(
                        'Category.title' => explode(",", $categories_home['SettingValue']['data'])
                    )
                ));
			}

	        if (empty($categories))
		        $categories = $this->Article->Category->find('all');

            $conditions = array(
                'Article.status' => 1,
                'Article.publish_time <=' => date('Y-m-d H:i:s')
            );

			$permissions = $this->getRelatedPermissions($this->permissionLookup(array('show' => true)));

		    if ($permissions['related']['articles']['view']['any'] == 0 && $this->Auth->user('id'))
		    	$conditions['User.id'] = $this->Auth->user('id');

	        $conditions = $this->Article->Category->getListConditions($conditions, $categories, $this->getRole(), 'view', $this->Auth->user('id'));

			$this->Paginator->settings = array(
				'limit' => $limit,
				'conditions' => $conditions,
				'order' => 'Article.created DESC'
			);

	        $this->request->data = $this->Article->getAllRelatedArticles(
	        	$this->Paginator->paginate('Article'),
		        false,
		        $categories
	        );

	        $this->set('articles', $this->request->data);
		}

		if ($path[0] == 'home' or $path[0] == 'denied')
        {
			$count = count($path);
			if (!$count)
				return $this->redirect('/');

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
		}
        else
        {
			$this->request->data = $this->Page->find('first', array(
				'conditions' => array(
					'Page.slug' => $path[0]
				),
                'contain' => array(
                    'User'
                )
			));

			if (!empty($this->request->data)) {
				$this->set('title_for_layout', $this->request->data['Page']['title']);
			} else {
	            $this->Session->setFlash('This page doesnt exist.', 'error');
	            $this->redirect('/');
			}

            $this->set('page', $this->request->data);
		}

		$this->view = implode('/', $path);
	}

    /**
    * This is the main admin page located at yoursite.com/admin
    *
	* Currently API calls are made to get the latest AdaptCMS.com news/blog and newest plugin/theme.
	*
	* The default AdaptCMS install has two dynamic blocks setup, pulling in the newest 5 users and articles.
    *
    * @return array of news, blog, newest_plugin and newest_theme
    */
	public function admin()
	{
		/*
		* API Component is used to connect to the adaptcms.com website
		*/
		$this->CmsApi = $this->Components->load('CmsApi');

        $data = $this->CmsApi->getAdminData();

		$this->set('news', $data['site-news']);
		$this->set('blog', $data['site-blogs']);
		$this->set('plugin', $data['plugins']);
		$this->set('theme', $data['themes']);
	}
}