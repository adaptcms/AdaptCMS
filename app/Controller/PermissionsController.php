<?php

class PermissionsController extends AppController {
	public $name = 'Permissions';

	public function admin_add()
	{
		$this->Permission->create();
		// die(debug($this->request->data));
		if ($this->Permission->save($this->request->data)) {
            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Permissions have been saved.', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array(
            	'controller' => 'Roles', 
            	'action' => 'admin_edit', 
            	$this->request->data['Permission']['role_id']));
        } else {
            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Permissions could not be saved.', 'default', array('class' => 'alert alert-error'));
            $this->redirect(array(
            	'controller' => 'Roles', 
            	'action' => 'admin_edit', 
            	$this->request->data['Permission']['role_id']));
        }
	}


}