<?php

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
    * @return associative array of settings data
    */
	public function admin_index()
	{
        $this->paginate = array(
            'order' => 'Setting.created DESC',
            'limit' => $this->pageLimit,
            'conditions' => array(
            	'Setting.deleted_time' => '0000-00-00 00:00:00'
            )
        );
        
        $this->request->data = $this->paginate('Setting');
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
                $this->Session->setFlash('Your setting has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your setting.', 'flash_error');
            }
        } 
	}

    /**
    * Before POST, sets request data to form and related fields/articles
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param id ID of the database entry, redirect to index if no permissions
    * @return associative array of settings data
    */
	public function admin_edit($id = null)
	{
		$this->Setting->id = $id;

	    if (!empty($this->request->data))
	    {
	        if ($this->Setting->save($this->request->data))
	        {
	            $this->Session->setFlash('Your setting has been updated.', 'flash_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your setting.', 'flash_error');
	        }
	    }

        $this->request->data = $this->Setting->find('first', array(
        	'conditions' => array(
        		'Setting.id' => $id,
        		'Setting.deleted_time' => '0000-00-00 00:00:00',
        	),
        	'contain' => array(
				'SettingValue' => array(
					'conditions' => array(
						'SettingValue.deleted_time' => '0000-00-00 00:00:00'
						)
					)
				)
        	)
        );
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
	    $this->Setting->id = $id;

	    if (!empty($permanent))
		{
			$delete_values = $this->Setting->SettingValue->deleteAll(array('SettingValue.setting_id' => $id));;
			$delete = $this->Setting->delete($id);
		} else {
			$delete_values = $this->Setting->SettingValue->updateAll(
	    		array(
	    			'SettingValue.deleted_time' => $this->Setting->dateTime()
	    		),
	    		array(
	    			'SettingValue.setting_id' => $id
	    		)
	    	);
	    	$delete_setting = $this->Setting->saveField('deleted_time', $this->Setting->dateTime());
		}

	    if ($delete_setting && $delete_values)
	    {
	        $this->Session->setFlash('The setting `'.$title.'` has been deleted.', 'flash_success');
	    } else {
	    	$this->Session->setFlash('The setting `'.$title.'` has NOT been deleted.', 'flash_error');
	    }

	    if (!empty($permanent)) {
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
	    $this->Setting->id = $id;

	    $save_values = $this->Setting->SettingValue->updateAll(
    		array(
    			'SettingValue.deleted_time' => '0000-00-00 00:00:00'
    		),
    		array(
    			'SettingValue.setting_id' => $id
    		)
    	);

    	$save_setting = $this->Setting->saveField('deleted_time', '0000-00-00 00:00:00');

	    if ($save_setting && $save_values)
	    {
	        $this->Session->setFlash('The setting `'.$title.'` has been restored.', 'flash_success');
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash('The setting `'.$title.'` has NOT been restored.', 'flash_error');
	        $this->redirect(array('action' => 'index'));
	    }
	}
}