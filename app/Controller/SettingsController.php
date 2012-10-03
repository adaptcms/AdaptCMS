<?php

class SettingsController extends AppController {
	public $name = 'Settings';

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

	public function admin_add()
	{
        if ($this->request->is('post')) {
            if ($this->Setting->save($this->request->data)) {
                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Your setting has been added.', 'default', array('class' => 'alert alert-success'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to add your setting.', 'default', array('class' => 'alert alert-error'));
            }
        } 
	}

	public function admin_edit($id = null)
	{

      $this->Setting->id = $id;

	    if ($this->request->is('get')) {
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
	    } else {

	        if ($this->Setting->save($this->request->data)) {
	            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Your setting has been updated.', 'default', array('class' => 'alert alert-success'));
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to update your setting.', 'default', array('class' => 'alert alert-error'));
	        }
	    }

	}

	public function admin_delete($id = null, $title = null)
	{
		if ($this->request->is('get')) {
	        throw new MethodNotAllowedException();
	    }

	    $this->Setting->id = $id;
	    if ($this->Setting->saveField('deleted_time', $this->Setting->dateTime())) {
	    	// $this->Setting->SettingValue->deleteAll(array('SettingValue.setting_id' => $id));
	    	$this->Setting->SettingValue->updateAll(
	    		array(
	    			'SettingValue.deleted_time' => $this->Setting->dateTime()
	    		),
	    		array(
	    			'SettingValue.setting_id' => $id
	    		)
	    	);

	        $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> The setting `'.$title.'` has been deleted.', 'default', array('class' => 'alert alert-success'));
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> The setting `'.$title.'` has NOT been deleted.', 'default', array('class' => 'alert alert-error'));
	        $this->redirect(array('action' => 'index'));
	    }
	}

}