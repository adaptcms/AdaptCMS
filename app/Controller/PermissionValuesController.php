<?php

class PermissionValuesController extends AppController {
	public $name = 'PermissionValues';

	public function admin_add()
	{
		if ($this->PermissionValue->save($this->request->data)) {
            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Permissions have been saved.', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array(
            	'controller' => 'roles', 
            	'action' => 'admin_edit', 
            	$this->request->data['PermissionValue']['role_id']));
        } else {
            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Permissions could not be saved.', 'default', array('class' => 'alert alert-error'));
            $this->redirect(array(
            	'controller' => 'roles', 
            	'action' => 'admin_edit', 
            	$this->request->data['PermissionValue']['role_id']));
        }
	}
}