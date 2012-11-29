<?php

class PermissionsController extends AppController {
	public $name = 'Permissions';

	public function admin_add()
	{
		$this->Permission->create();
		// die(debug($this->request->data));
		if ($this->Permission->save($this->request->data)) {
            $this->Session->setFlash('Permissions have been saved.', 'flash_success');
            $this->redirect(array(
            	'controller' => 'Roles', 
            	'action' => 'admin_edit', 
            	$this->request->data['Permission']['role_id']));
        } else {
            $this->Session->setFlash('Permissions could not be saved.', 'flash_error');
            $this->redirect(array(
            	'controller' => 'Roles', 
            	'action' => 'admin_edit', 
            	$this->request->data['Permission']['role_id']));
        }
	}
}