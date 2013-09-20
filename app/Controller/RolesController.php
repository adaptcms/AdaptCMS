<?php
App::uses('AppController', 'Controller');

/**
 * Class RolesController
 */
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
    * @return array Array of roles data
    */
	public function admin_index()
	{
		$conditions = array();

		if (isset($this->request->named['trash']))
			$conditions['Role.only_deleted'] = true;

		$this->Paginator->settings = array(
            'conditions' => $conditions
        );

		$this->request->data = $this->Paginator->paginate('Role');
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
    * @param integer $id id of the database entry, redirect to index if no permissions
    * @return array Array of block data
    */
	public function admin_edit($id)
	{
      	$this->Role->id = $id;

	    if (!empty($this->request->data))
        {
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
    * @param integer $id id of the database entry, redirect to index if no permissions
    * @param string $title Title of this entry, used for flash message
     *
    * @return void
    */
	public function admin_delete($id, $title = null)
	{
	    $this->Role->id = $id;

		$data = $this->Role->findById($id);
		$this->hasAccessToItem($data);

		$permanent = $this->Role->remove($data);

		$this->Session->setFlash('The role `'.$title.'` has been deleted.', 'flash_success');

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
	    $this->Role->id = $id;

	    if ($this->Role->restore())
	    {
	        $this->Session->setFlash('The role `'.$title.'` has been restored.', 'flash_success');
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash('The role `'.$title.'` has NOT been restored.', 'flash_error');
	        $this->redirect(array('action' => 'index'));
	    }
	}
}