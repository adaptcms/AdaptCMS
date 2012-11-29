<?php

class SettingValuesController extends AppController {
	public $name = 'SettingValues';

	public function admin_add()
	{       
        $this->SettingValue->create();

        if (!empty($this->request->data['FieldData'])) {
            $this->request->data['SettingValue']['data'] = 
                str_replace("'","",json_encode($this->request->data['FieldData']));
        }

		if ($this->SettingValue->save($this->request->data)) {
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

    public function admin_edit($id, $redirect_id = null)
    {
        foreach ($this->request->data['SettingValue'] as $key => $data) {
            $set_data[] = $data;
        }

        if ($this->SettingValue->saveMany($set_data)) {
            $this->Session->setFlash('Settings have been saved.', 'flash_success');
        } else {
            $this->Session->setFlash('Settings could not be saved.', 'flash_error');
        }

        if (!empty($redirect_id)) {
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