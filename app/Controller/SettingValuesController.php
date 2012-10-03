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
            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Setting has been saved.', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array(
            	'controller' => 'Settings', 
            	'action' => 'admin_edit', 
            	$this->request->data['SettingValue']['setting_id']));
        } else {
            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Setting could not be saved.', 'default', array('class' => 'alert alert-error'));
            $this->redirect(array(
            	'controller' => 'Settings', 
            	'action' => 'admin_edit', 
            	$this->request->data['SettingValue']['setting_id']));
        }
	}

    public function admin_edit($id)
    {
        $pass = 0;
        $total = 0;

        foreach ($this->request->data['SettingValue'] as $key => $data) {
            $set_data[] = $data;
        }

        if ($this->SettingValue->saveMany($set_data)) {
            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Settings have been saved.', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array(
                'controller' => 'Settings', 
                'action' => 'admin_edit', 
                $id));
        } else {
            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Settings could not be saved.', 'default', array('class' => 'alert alert-error'));
            $this->redirect(array(
                'controller' => 'Settings', 
                'action' => 'admin_edit', 
                $id));
        }
    }

}