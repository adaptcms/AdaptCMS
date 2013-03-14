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

		if ($this->params->action == 'admin_add' || $this->params->action == 'admin_edit')
		{
	        $forums = $this->Forum->find('all', array(
	        	'conditions' => array(
	        		'Forum.deleted_time' => '0000-00-00 00:00:00'
	        	),
	        	'order' => 'Forum.ord ASC'
	        ));

	        $categories = $this->Forum->ForumCategory->find('list');

	        $this->set(compact('forums', 'categories'));
		}
	}

    /**
    * Returns a paginated index of Forums
    *
    * @return associative array of forums data
    */
	public function admin_index()
	{
		$conditions = array();

		if (!isset($this->params->named['trash'])) {
	        $conditions['Forum.deleted_time'] = '0000-00-00 00:00:00';
	    } else {
	        $conditions['Forum.deleted_time !='] = '0000-00-00 00:00:00';
        }

	    if ($this->permissions['any'] == 0)
	    {
	    	$conditions['User.id'] = $this->Auth->user('id');
	    }

        $this->paginate = array(
            'order' => 'Forum.created DESC',
            'limit' => $this->pageLimit,
            'conditions' => $conditions,
            'contain' => array(
            	'User',
            	'ForumCategory'
            )
        );
        
		$this->request->data = $this->paginate('Forum');
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
        	$this->Forum->create();

    		$this->request->data['Forum']['user_id'] = $this->Auth->user('id');

            if ($this->Forum->save($this->request->data))
            {
                $this->Session->setFlash('Your forum has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your forum.', 'flash_error');
            }
        }
	}

    /**
    * Before POST, sets request data to form
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param id ID of the database entry, redirect to index if no permissions
    * @return associative array of forum data
    */
	public function admin_edit($id = null)
	{
      	$this->Forum->id = $id;

	    if (!empty($this->request->data))
	    {
	    	$this->request->data['Forum']['user_id'] = $this->Auth->user('id');

	        if ($this->Forum->save($this->request->data))
	        {
	            $this->Session->setFlash('Your forum has been updated.', 'flash_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your forum.', 'flash_error');
	        }
	    }

        $this->request->data = $this->Forum->find('first', array(
        	'conditions' => array(
        		'Forum.id' => $id
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
	    $this->Forum->id = $id;

        $data = $this->Forum->find('first', array(
        	'conditions' => array(
        		'Forum.id' => $id
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
	    	$delete = $this->Forum->delete($id);
	    } else {
	    	$delete = $this->Forum->saveForum('deleted_time', $this->Forum->dateTime());
	    }

	    if ($delete)
	    {
	        $this->Session->setFlash('The forum `'.$title.'` has been deleted.', 'flash_success');
	    } else {
	    	$this->Session->setFlash('The forum `'.$title.'` has NOT been deleted.', 'flash_error');
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
    * @return direct
    */
	public function admin_restore($id = null, $title = null)
	{
	    $this->Forum->id = $id;

        $data = $this->Forum->find('first', array(
        	'conditions' => array(
        		'Forum.id' => $id
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

	    if ($this->Forum->saveForum('deleted_time', '0000-00-00 00:00:00'))
	    {
	        $this->Session->setFlash('The forum `'.$title.'` has been restored.', 'flash_success');
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash('The forum `'.$title.'` has NOT been restored.', 'flash_error');
	        $this->redirect(array('action' => 'index'));
	    }
	}

    /**
    * This will go through a list of forums from the order JS. The new order is then saved.
    *
    * @return json_encode array
    */
    public function admin_ajax_order()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;

        $data = array();
        $i = 0;

        foreach($this->request->data['Forum']['forum_ids'] as $key => $forum) {
            if (!empty($forum) && $forum > 0) {
                $data[$i]['id'] = $forum;
                $data[$i]['ord'] = $key;
                
                $i++;
            }
        }

        if (!empty($data) && $this->Forum->saveAll($data)) {
            return json_encode(array(
                'status' => true,
                'message' => '
                    <div id="flashMessage" class="alert alert-success">
                        <button class="close" data-dismiss="alert" style="margin-top: 0;float: right">×</button>
                        <strong>Success</strong> Forum order has been saved.
                    </div>'
            ));
        } else {
            return json_encode(array(
                'status' => false,
                'message' => '
                    <div id="flashMessage" class="alert alert-error">
                        <button class="close" data-dismiss="alert" style="margin-top: 0;float: right">×</button>
                        <strong>Error</strong> Forum order could not be saved, please try again.
                    </div>'
            ));
        }
    }

    public function admin_ajax_forums()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;

        $fields = $this->Forum->find('all', array(
            'conditions' => array(
                'Forum.category_id' => $this->request->data['Forum']['category_id']
            ),
            'order' => 'Forum.ord ASC'
        ));

        $original = "<span>".$this->request->data['Forum']['title']."</span> <i class='icon icon-question-sign' 
            data-content='".$this->request->data['Forum']['description']."' 
            data-title='".$this->request->data['Forum']['title']."'></i> 

            <span class='label label-info pull-right'>
                Current Forum
            </span>";

        foreach($fields as $key => $field) {
            $data .= "<li class='btn' id='".$field['Forum']['id']."'><i class='icon icon-move'></i> ";

            if ($field['Forum']['id'] == $this->request->data['Forum']['id']) {
                $data .= $original;
                $current = 1;
            } else {
                $data .= "<span>".$field['Forum']['title']."</span> 
                <i class='icon icon-question-sign' data-content='".$field['Forum']['description']."' data-title='".$field['Forum']['title']."'></i>";
            }

            $data .= "</li>
            ";
        }

        if (empty($current)) {
            $key = count($data);
            if (empty($this->request->data['Forum']['id'])) {
                $this->request->data['Forum']['id'] = 0;
            }

            $data .= "<li class='btn' id='".$this->request->data['Forum']['id']."'><i class='icon icon-move'></i> ".$original." </li>
            ";
        }

        return json_encode(array(
            'status' => true,
            'data' => $data
        ));
    }

    /**
    * Returns a list of Categories with related Forums
    *
    * @return associative array of category data
    */
    public function index()
    {
        $this->request->data = $this->Forum->ForumCategory->find('all', array(
            'conditions' => array(
                'ForumCategory.deleted_time' => '0000-00-00 00:00:00'
            ),
            'contain' => array(
                'Forum' => array(
                    'conditions' => array(
                        'Forum.deleted_time' => '0000-00-00 00:00:00'
                    ),
                    'order' => 'Forum.ord ASC'
                )
            ),
            'order' => 'ForumCategory.ord ASC'
        ));

        $this->set('categories', $this->request->data);
    }

    /**
    * Returns a paginated list of topics along with forum data
    *
    * @return associative array of topic data
    */
    public function view($slug)
    {
        $forum = $this->Forum->findBySlug($slug);

        if (empty($forum['Forum']))
        {
            $this->Session->setFlash('Forum `' . $slug . '` could not be found.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }

        $this->paginate = array(
            'conditions' => array(
                'Forum.deleted_time' => '0000-00-00 00:00:00',
                'Forum.slug' => $slug,
                'ForumTopic.deleted_time' => '0000-00-00 00:00:00'
            ),
            'contain' => array(
                'Forum',
                'User'
            ),
            'order' => 'ForumTopic.created ASC'
        );

        $this->set('topics', $this->paginate('ForumTopic'));
        $this->set('forum', $forum['Forum']);
    }
}