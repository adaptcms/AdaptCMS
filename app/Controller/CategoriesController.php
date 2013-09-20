<?php
App::uses('AppController', 'Controller');

/**
 * Class CategoriesController
 *
 * @property Category $Category
 */
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

    public $cacheAction = array(
        'view' => '1 day'
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
    * Returns a paginated index of Categories
    *
    * @return array Array of categories data
    */
	public function admin_index()
	{
		$conditions = array();

	    if ($this->permissions['any'] == 0)
	    	$conditions['User.id'] = $this->Auth->user('id');

		if (isset($this->request->named['trash']))
			$conditions['Category.only_deleted'] = true;

		$this->Paginator->settings = array(
            'conditions' => $conditions,
            'contain' => array(
            	'User'
            )
        );
        
		$this->request->data = $this->Paginator->paginate('Category');
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
                $this->Session->setFlash('Your category has been added. Now you may want to add some fields to the category.', 'flash_success');
                $this->redirect(array(
                    'controller' => 'fields',
                    'action' => 'add',
                    $this->Category->id
                ));
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
    * @param integer $id id of the database entry, redirect to index if no permissions
    * @return array Array of category data
    */
	public function admin_edit($id)
	{
      	$this->Category->id = $id;

      	$this->Paginator->settings = array(
      		'conditions' => array(
      			'Article.category_id' => $id
      		),
      		'contain' => array(
      			'User'
      		),
      		'limit' => 7
      	);

      	$articles = $this->Paginator->paginate('Article');

      	$fields = $this->Category->Field->find('all', array(
      		'conditions' => array(
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

        $this->request->data = $this->Category->findById($id);
		$this->hasAccessToItem($this->request->data);
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
	    $this->Category->id = $id;

        $data = $this->Category->findById($id);
		$this->hasAccessToItem($data);

		$permanent = $this->Category->remove($data);

		$this->Session->setFlash('The category `'.$title.'` has been deleted.', 'flash_success');

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
    * @return mixed
    */
	public function admin_restore($id, $title = null)
	{
	    $this->Category->id = $id;

        $data = $this->Category->findById($id);
		$this->hasAccessToItem($data);

	    if ($this->Category->restore())
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
	* @param string $slug Slug of the Category
	* @return mixed
	*/
	public function view($slug)
	{
        if (!empty($slug))
        {
            $slug = $this->Category->slug($slug);
            $category = $this->Category->findBySlug($slug);
        }

        if (empty($category))
        {
            $this->Session->setFlash('Invalid Category', 'flash_error');
            return $this->redirect(array(
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
            'Article.publish_time <=' => date('Y-m-d H:i:s')
		);

	    if ($this->permissions['any'] == 0 && $this->Auth->user('id'))
	    	$conditions['User.id'] = $this->Auth->user('id');

		$this->Paginator->settings = array(
			'order' => 'Article.created DESC',
			'conditions' => $conditions,
			'limit' => $limit
		);

		$articles = $this->Paginator->paginate('Article');

		$this->request->data = $this->Category->Article->getAllRelatedArticles(
			$articles
		);

		$this->set('category', $category['Category']);
		$this->set('article', $this->request->data);

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
	* @return string
	*/
	public function index()
	{
		$data = $this->Category->find('all', array(
			'fields' => array(
				'id', 'title', 'slug', 'created'
			)
		));

		$this->layout = '';
		$this->autoRender = false;

		echo json_encode($data);
	}
}