<?php
App::uses('AppController', 'Controller');

/**
 * Class SettingValuesController
 */
class SettingValuesController extends AppController
{
    /**
    * Name of the Controller, 'SettingValues'
    */
	public $name = 'SettingValues';

    /**
    * Returns nothing before post
    *
    * On POST, returns error flash or success flash and redirect to category edit on success
    *
    * @return redirect and flash message
    */
	public function admin_add()
	{ 
        if (!empty($this->request->data))
        {
            $this->SettingValue->create();

    		if ($this->SettingValue->save($this->request->data))
            {
                $this->Session->setFlash('Setting has been saved.', 'flash_success');
                $this->redirect(array(
                	'controller' => 'settings', 
                	'action' => 'admin_edit', 
                	$this->request->data['SettingValue']['setting_id']));
            } else {
                $this->Session->setFlash('Setting could not be saved.', 'flash_error');
                $this->redirect(array(
                	'controller' => 'settings', 
                	'action' => 'admin_edit', 
                	$this->request->data['SettingValue']['setting_id']));
            }
        }
	}

    /**
    * Before POST, sets request data to form and related fields/articles
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param id ID of the database entry
    * @param redirect_id this is used when editing a user for user settings, null by default
    * @return associative array of settings data
    */
    public function admin_edit($id, $redirect_id = null)
    {
        if ($this->SettingValue->saveMany($this->request->data))
        {
            $this->Session->setFlash('Settings have been saved.', 'flash_success');
        } else {
            $this->Session->setFlash('Settings could not be saved.', 'flash_error');
        }

        if (!empty($redirect_id))
        {
            $this->redirect(array(
                'controller' => 'users', 
                'action' => 'admin_edit', 
                $redirect_id
            ));
        } else {
            $this->redirect(array(
                'controller' => 'settings', 
                'action' => 'admin_edit', 
                $id
            ));
        }
    }
}