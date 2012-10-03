<?php

class PollsController extends AppController {
	public $name = 'Polls';

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout('admin');
	}

	public function admin_index()
	{
		$this->paginate = array(
            'order' => 'Poll.created DESC',
            'limit' => 10,
            'conditions' => array(
            	'Poll.deleted_time' => '0000-00-00 00:00:00'
            )
        );
        
		$this->request->data = $this->paginate('Poll');
	}

	public function admin_add()
	{

		$this->set('articles', $this->Poll->Article->find('list'));

        if ($this->request->is('post')) {
            if ($this->Poll->saveAssociated($this->request->data)) {
                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Your poll has been added.', 'default', array('class' => 'alert alert-success'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to add your poll.', 'default', array('class' => 'alert alert-error'));
            }
        } 
	}

	public function admin_edit($id = null)
	{

      $this->Poll->id = $id;

	    if ($this->request->is('get')) {
	        $this->request->data = $this->Poll->find('first', array(
	        	'conditions' => array(
	        		'Poll.id' => $id
	        	),
	        	'contain' => array(
        			'PluginPollValue'
        			)
	        	)
	        );
	        $this->set('articles', $this->Poll->Article->find('list'));
	    } else {
	    		// die(debug($this->request->data['PollValue']));
	    		foreach($this->request->data['PluginPollValue'] as $data) {
	    			if (!empty($data['delete']) && $data['delete'] == 1) {
	    				$this->Poll->PluginPollValue->delete($data['id']);
	    			} elseif (empty($data['id'])) {
	    				$pollValue['PluginPollValue'] = array(
	    					'title' => $data['title'],
	    					'plugin_poll_id' => $id
	    				);
	    				// die(debug($pollValue));
	    				$this->Poll->PluginPollValue->create();
	    				$this->Poll->PluginPollValue->save($pollValue);
	    				unset($pollValue);
	    			} else {
	    				$this->Poll->PluginPollValue->id = $data['id'];
	    				$this->Poll->PluginPollValue->saveField('title', $data['title']);
	    			}
	    		}
	    		unset($this->request->data['PluginPollValue']);
        		
	        if ($this->Poll->save($this->request->data)) {
	            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Your poll has been updated.', 'default', array('class' => 'alert alert-success'));
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to update your poll.', 'default', array('class' => 'alert alert-error'));
	        }
	    }

	}

	public function admin_delete($id = null, $title = null)
	{

		if ($this->request->is('post')) {
	        throw new MethodNotAllowedException();
	    }

	    $this->Poll->id = $id;
	    if ($this->Poll->saveField('deleted_time', $this->Poll->dateTime())) {
	    	// $this->Poll->PluginPollValue->deleteAll(array('PluginPollValue.plugin_poll_id' => $id));

	        $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> The poll `'.$title.'` has been deleted.', 'default', array('class' => 'alert alert-success'));
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> The poll `'.$title.'` has NOT been deleted.', 'default', array('class' => 'alert alert-error'));
	        $this->redirect(array('action' => 'index'));
	    }
	}

}