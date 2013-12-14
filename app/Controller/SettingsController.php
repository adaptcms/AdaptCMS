<?php
App::uses('AppController', 'Controller');

/**
 * Class SettingsController
 */
class SettingsController extends AppController
{
    /**
    * Name of the Controller, 'Settings'
    */
	public $name = 'Settings';

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
    * Returns a paginated index of Settings
    *
    * @return array Array of settings data
    */
	public function admin_index()
	{
        $conditions = array();

        if (isset($this->request->named['trash']))
            $conditions['Setting.only_deleted'] = true;

        $this->Paginator->settings = array(
            'conditions' => $conditions
        );
        
        $this->request->data = $this->Paginator->paginate('Setting');
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
            if ($this->Setting->save($this->request->data))
            {
                $this->Session->setFlash('Your setting has been added.', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your setting.', 'error');
            }
        } 
	}

    /**
    * Before POST, sets request data to form and related fields/articles
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param integer $id id of the database entry, redirect to index if no permissions
    * @return array Array of settings data
    */
	public function admin_edit($id)
	{
		$this->Setting->id = $id;

	    if (!empty($this->request->data))
	    {
	        if ($this->Setting->save($this->request->data))
	        {
	            $this->Session->setFlash('Your setting has been updated.', 'success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your setting.', 'error');
	        }
	    }

        $this->request->data = $this->Setting->find('first', array(
        	'conditions' => array(
        		'Setting.id' => $id
        	),
        	'contain' => array(
				'SettingValue'
        	)
        ));
	}

    /**
    * If item has no delete time, then initial deletion is to the trash area (making it in-active on site, if applicable)
    *
    * But if it has a deletion time, meaning it is in the trash, deleting it the second time is permanent.
    *
    * @param integer $id id of the database entry, redirect to index if no permissions
    * @param string $title Title of this entry, used for flash message
    * @return void
    */
	public function admin_delete($id, $title = null)
	{
	    $this->Setting->id = $id;

		$data = $this->Setting->findById($id);
		$this->hasAccessToItem($data);

		$permanent = $this->Setting->remove($data);

		$this->Session->setFlash('The setting category `'.$title.'` has been deleted.', 'success');

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
	    $this->Setting->id = $id;

	    if ($this->Setting->restore())
	    {
	        $this->Session->setFlash('The setting `'.$title.'` has been restored.', 'success');
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash('The setting `'.$title.'` has NOT been restored.', 'error');
	        $this->redirect(array('action' => 'index'));
	    }
	}
}