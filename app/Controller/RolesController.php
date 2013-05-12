<?php

class RolesController extends AppController
{
    /**
    * Name of the Controller, 'Roles'
    */
	public $name = 'Roles';

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
    * Returns a paginated index of Roles
    *
    * @return associative array of roles data
    */
	public function admin_index()
	{
		$conditions = array();

		if (!isset($this->params->named['trash'])) {
			$conditions['Role.deleted_time'] = '0000-00-00 00:00:00';
		} else {
			$conditions['Role.deleted_time !='] = '0000-00-00 00:00:00';
        }

		$this->paginate = array(
            'order' => 'Role.created DESC',
            'limit' => $this->pageLimit,
            'conditions' => $conditions
        );

		$this->request->data = $this->paginate('Role');
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
        	$this->Role->create();

        	$permissions = $this->Role->Permission->find('all', array(
        		'conditions' => array(
        			'Permission.role_id' => $this->request->data['Role']['role_id']
        		)
        	));

            if ($this->Role->save($this->request->data))
            {
	        	foreach($permissions as $key => $permission)
	        	{
	        		$permissions[$key] = $permission;
	        		$permissions[$key]['Permission']['role_id'] = $this->Role->id;
	        		unset($permissions[$key]['Permission']['id']);
	        	}

            	$this->Role->Permission->saveAll($permissions);

                $this->Session->setFlash('Your role has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your role.', 'flash_error');
            }
        }

        $roles = $this->Role->find('list');
        $this->set(compact('roles'));
	}

    /**
    * Before POST, sets request data to form
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param id ID of the database entry, redirect to index if no permissions
    * @return associative array of block data
    */
	public function admin_edit($id = null)
	{
      	$this->Role->id = $id;

	    if (!empty($this->request->data))
        {
	    	$this->request->data['Role']['title'] = $this->slug($this->request->data['Role']['title']);

	        if ($this->Role->saveAll($this->request->data, array('deep' => true)))
	        {
	            $this->Session->setFlash('Your role has been updated.', 'flash_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your role.', 'flash_error');
	        }
	    }

        $this->request->data = $this->Role->find('first', array(
        	'conditions' => array(
        		'Role.id' => $id
        	),
        	'contain' => array(
				'Permission' => array(
					'order' => 'Permission.controller ASC, Permission.action ASC'
				)
        	)
        ));

        foreach($this->request->data['Permission'] as $key => $row)
        {
        	$this->request->data['Permission']
        		[$row['controller']][] = $row;
        	unset($this->request->data['Permission'][$key]);
        }

        $this->request->data['Modules'] = $this->Role->Permission->Module->find('list');
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
	    $this->Role->id = $id;

		if (!empty($permanent))
		{
	    	$delete = $this->Role->delete($id);
	    } else {
	    	$delete = $this->Role->saveField('deleted_time', $this->Role->dateTime());
	    }

	    if ($delete)
	    {
	        $this->Session->setFlash('The role `'.$title.'` has been deleted.', 'flash_success');
	    } else {
	    	$this->Session->setFlash(
	    		'The role `'.$title.'` has NOT been deleted. 
	    		Make sure there is at least one role type of none (for admins), one active member role and one guest role.', 
	    		'flash_error'
	    	);
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
	    $this->Role->id = $id;

	    if ($this->Role->saveField('deleted_time', '0000-00-00 00:00:00'))
	    {
	        $this->Session->setFlash('The role `'.$title.'` has been restored.', 'flash_success');
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash('The role `'.$title.'` has NOT been restored.', 'flash_error');
	        $this->redirect(array('action' => 'index'));
	    }
	}
}