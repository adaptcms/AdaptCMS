<?php

class ForumsController extends AdaptbbAppController
{
    /**
    * Name of the Controller, 'Forums'
    */
	public $name = 'Forums';

    /**
    * array of permissions for this page
    */
	private $permissions;

    /**
    * In this beforeFilter we will get the permissions to be used in the view files
    *
    * Also set to the view a list of forums for forum order
    */
	public function beforeFilter()
	{
		parent::beforeFilter();
	
		$this->permissions = $this->getPermissions();

		if ($this->request->action == 'admin_add' || $this->request->action == 'admin_edit')
		{
	        $forums = $this->Forum->find('all', array(
	        	'order' => 'Forum.ord ASC'
	        ));

	        $categories = $this->Forum->ForumCategory->find('list');

	        $this->set(compact('forums', 'categories'));
		}
	}

    /**
    * Returns a paginated index of Forums
    *
    * @return array of forums data
    */
	public function admin_index()
	{
		$conditions = array();

		if (isset($this->request->named['trash']))
	        $conditions['Forum.only_deleted'] = true;

	    if ($this->permissions['any'] == 0)
	    	$conditions['User.id'] = $this->Auth->user('id');

        $this->Paginator->settings = array(
            'conditions' => $conditions,
            'contain' => array(
            	'User',
            	'ForumCategory'
            )
        );
        
		$this->request->data = $this->Paginator->paginate('Forum');
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
	        $this->Forum->saveForumOrder($this->request->data);

	        $this->Forum->create();

    		$this->request->data['Forum']['user_id'] = $this->Auth->user('id');

            if ($this->Forum->save($this->request->data))
            {
                $this->Session->setFlash('Your forum has been added.', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your forum.', 'error');
            }
        }
	}

    /**
    * Before POST, sets request data to form
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param integer $id ID of the database entry, redirect to index if no permissions
    * @return array of forum data
    */
	public function admin_edit($id)
	{
      	$this->Forum->id = $id;

	    if (!empty($this->request->data))
	    {
	    	$this->request->data['Forum']['user_id'] = $this->Auth->user('id');

		    $this->Forum->saveForumOrder($this->request->data);

	        if ($this->Forum->save($this->request->data))
	        {
	            $this->Session->setFlash('Your forum has been updated.', 'success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your forum.', 'error');
	        }
	    }

		$this->request->data = $this->Forum->findById($id);
		$this->hasAccessToItem($this->request->data);
	}

    /**
    * If item has no delete time, then initial deletion is to the trash area (making it in-active on site, if applicable)
    *
    * But if it has a deletion time, meaning it is in the trash, deleting it the second time is permanent.
    *
    * @param integer $id ID of the database entry, redirect to index if no permissions
    * @param string $title Title of this entry, used for flash message
    * @return void
    */
	public function admin_delete($id, $title = null)
	{
	    $this->Forum->id = $id;

		$data = $this->Forum->findById($id);
		$this->hasAccessToItem($data);

		$permanent = $this->Forum->remove($data);

		$this->Session->setFlash('The forum `'.$title.'` has been deleted.', 'success');

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
	    $this->Forum->id = $id;

		$data = $this->Forum->findById($id);
		$this->hasAccessToItem($data);

	    if ($this->Forum->restore())
	    {
	        $this->Session->setFlash('The forum `'.$title.'` has been restored.', 'success');
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash('The forum `'.$title.'` has NOT been restored.', 'error');
	        $this->redirect(array('action' => 'index'));
	    }
	}

	/**
	 * Admin Ajax Forums
	 *
	 * @return CakeResponse
	 */
	public function admin_ajax_forums()
    {
        $forums = $this->Forum->find('all', array(
            'conditions' => array(
                'Forum.category_id' => $this->request->data['Forum']['category_id']
            ),
            'order' => 'Forum.ord ASC'
        ));

	    return $this->_ajaxResponse('Adaptbb.admin_ajax_forums', array(
		    'forums' => $forums,
		    'original' => $this->request->data['Forum']
	    ));
    }

    /**
    * Returns a list of Categories with related Forums
    *
    * @return array of category data
    */
    public function index()
    {
        $this->request->data = $this->Forum->ForumCategory->find('all', array(
            'contain' => array(
                'Forum' => array(
                    'order' => 'Forum.ord ASC'
                )
            ),
            'order' => 'ForumCategory.ord ASC'
        ));

        $this->set('categories', $this->Forum->getIndexStats($this->request->data));
    }

    /**
    * Returns a paginated list of topics along with forum data
    *
    * @param string $slug slug of forum
    * @return array of topic data
    */
    public function view($slug = null)
    {
        if (empty($slug) && !empty($this->params['slug']))
        {
            $slug = $this->params['slug'];
        }

        $forum = $this->Forum->findBySlug($slug);

        if (empty($forum['Forum']))
        {
            $this->Session->setFlash('Forum `' . $slug . '` could not be found.', 'error');
            $this->redirect(array('action' => 'index'));
        }

        $cond = array(
            'conditions' => array(
                'Forum.slug' => $slug,
                'ForumTopic.topic_type !=' => array('topic', 'sticky')
            ),
            'contain' => array(
                'Forum',
                'User'
            )
        );

        $announcements = $this->Forum->ForumTopic->getStats( $this->Forum->ForumTopic->find('all', $cond) );

        $cond['conditions']['ForumTopic.topic_type !='] = 'announcement';
        $cond['order'] = 'ForumTopic.topic_type ASC, ForumTopic.created DESC';
        $cond['limit'] = Configure::read('Adaptbb.num_topics_per_page_forum');

        $this->Paginator->settings = $cond;

        $this->set('topics', $this->Forum->ForumTopic->getStats($this->Paginator->paginate('ForumTopic')) );
        $this->set('forum', $forum['Forum']);
        $this->set(compact('announcements'));
    }
}