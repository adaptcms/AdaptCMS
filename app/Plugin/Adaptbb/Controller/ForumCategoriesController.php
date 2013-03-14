<?php

class ForumCategoriesController extends AdaptbbAppController
{
    /**
    * Name of the Controller, 'ForumCategories'
    */
	public $name = 'ForumCategories';

    /**
    * array of permissions for this page
    */
	private $permissions;

    /**
    * In this beforeFilter we will get the permissions to be used in the view files
    *
    * Also set to the view a list of forum categories for category order
    */
	public function beforeFilter()
	{
		parent::beforeFilter();
	
		$this->permissions = $this->getPermissions();

		if ($this->params->action == 'admin_add' || $this->params->action == 'admin_edit')
		{
	        $categories = $this->ForumCategory->find('all', array(
	        	'conditions' => array(
	        		'ForumCategory.deleted_time' => '0000-00-00 00:00:00'
	        	),
	        	'order' => 'ForumCategory.ord ASC'
	        ));

	        $this->set(compact('categories'));
		}
	}

    /**
    * Returns a paginated index of Forum Categories
    *
    * @return associative array of categories data
    */
	public function admin_index()
	{
		$conditions = array();

		if (!isset($this->params->named['trash'])) {
	        $conditions['ForumCategory.deleted_time'] = '0000-00-00 00:00:00';
	    } else {
	        $conditions['ForumCategory.deleted_time !='] = '0000-00-00 00:00:00';
        }

	    if ($this->permissions['any'] == 0)
	    {
	    	$conditions['User.id'] = $this->Auth->user('id');
	    }

        $this->paginate = array(
            'order' => 'ForumCategory.created DESC',
            'limit' => $this->pageLimit,
            'conditions' => $conditions,
            'contain' => array(
            	'User'
            )
        );
        
		$this->request->data = $this->paginate('ForumCategory');
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
        	$this->ForumCategory->create();

    		$this->request->data['ForumCategory']['user_id'] = $this->Auth->user('id');

            if ($this->ForumCategory->save($this->request->data))
            {
                $this->Session->setFlash('Your forum category has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your forum category.', 'flash_error');
            }
        }
	}

    /**
    * Before POST, sets request data to form
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param id ID of the database entry, redirect to index if no permissions
    * @return associative array of forum category data
    */
	public function admin_edit($id = null)
	{
      	$this->ForumCategory->id = $id;

	    if (!empty($this->request->data))
	    {
	    	$this->request->data['ForumCategory']['user_id'] = $this->Auth->user('id');

	        if ($this->ForumCategory->save($this->request->data))
	        {
	            $this->Session->setFlash('Your forum category has been updated.', 'flash_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your forum category.', 'flash_error');
	        }
	    }

        $this->request->data = $this->ForumCategory->find('first', array(
        	'conditions' => array(
        		'ForumCategory.id' => $id
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
	    $this->ForumCategory->id = $id;

        $data = $this->ForumCategory->find('first', array(
        	'conditions' => array(
        		'ForumCategory.id' => $id
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
	    	$delete = $this->ForumCategory->delete($id);
	    } else {
	    	$delete = $this->ForumCategory->saveField('deleted_time', $this->ForumCategory->dateTime());
	    }

	    if ($delete)
	    {
	        $this->Session->setFlash('The forum category `'.$title.'` has been deleted.', 'flash_success');
	    } else {
	    	$this->Session->setFlash('The forum category `'.$title.'` has NOT been deleted.', 'flash_error');
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
	    $this->ForumCategory->id = $id;

        $data = $this->ForumCategory->find('first', array(
        	'conditions' => array(
        		'ForumCategory.id' => $id
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

	    if ($this->ForumCategory->saveField('deleted_time', '0000-00-00 00:00:00'))
	    {
	        $this->Session->setFlash('The forum category `'.$title.'` has been restored.', 'flash_success');
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash('The forum category `'.$title.'` has NOT been restored.', 'flash_error');
	        $this->redirect(array('action' => 'index'));
	    }
	}

    /**
    * This will go through a list of categories from the order JS. The new order is then saved.
    *
    * @return json_encode array
    */
    public function admin_ajax_order()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;

        $data = array();
        $i = 0;

        foreach($this->request->data['ForumCategory']['category_ids'] as $key => $category) {
            if (!empty($category) && $category > 0) {
                $data[$i]['id'] = $category;
                $data[$i]['ord'] = $key;
                
                $i++;
            }
        }

        if (!empty($data) && $this->ForumCategory->saveAll($data)) {
            return json_encode(array(
                'status' => true,
                'message' => '
                    <div id="flashMessage" class="alert alert-success">
                        <button class="close" data-dismiss="alert" style="margin-top: 0;float: right">×</button>
                        <strong>Success</strong> Category order has been saved.
                    </div>'
            ));
        } else {
            return json_encode(array(
                'status' => false,
                'message' => '
                    <div id="flashMessage" class="alert alert-error">
                        <button class="close" data-dismiss="alert" style="margin-top: 0;float: right">×</button>
                        <strong>Error</strong> Category order could not be saved, please try again.
                    </div>'
            ));
        }
    }
}