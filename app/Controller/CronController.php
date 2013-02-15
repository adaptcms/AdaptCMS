<?php

class CronController extends AppController
{
	public $name = 'Cron';
	public $uses = array(
		'Cron'
	);

	public function beforeFilter()
	{
		parent::beforeFilter();

		$modules_list = $this->Cron->Module->find('all', array(
			'order' => 'is_plugin ASC, title ASC'
		));

		foreach($modules_list as $key => $module) {
			$id = $module['Module']['id'];

			if ($module['Module']['is_plugin'] == 1) {
				$modules[$id] = 'Plugin - ' . $module['Module']['title'];
			} else {
				$modules[$id] = $module['Module']['title'];
			}
		}

		$period_amount = array();
		for($i = 1; $i <= 24; $i++) {
			$period_amount[$i] = $i;
		}

		$this->set(compact('modules', 'period_amount'));
	}

	public function admin_index()
	{
		if (!isset($this->params->named['trash'])) {
			$this->paginate = array(
	            'order' => 'Cron.created DESC',
	            'limit' => $this->pageLimit,
	            'conditions' => array(
	            	'Cron.deleted_time' => '0000-00-00 00:00:00'
	            )
	        );
		} else {
			$this->paginate = array(
	            'order' => 'Cron.created DESC',
	            'limit' => $this->pageLimit,
	            'conditions' => array(
	            	'Cron.deleted_time !=' => '0000-00-00 00:00:00'
	            )
	        );
        }
        
		$this->request->data = $this->paginate('Cron');
	}

	public function admin_add()
	{
        if ($this->request->is('post')) {
        	$amount = $this->request->data['Cron']['period_amount'];
        	$type = $this->request->data['Cron']['period_type'];
        	$this->request->data['Cron']['run_time'] = date('Y-m-d H:i:s', strtotime('+' . $amount . ' '.$type));

            if ($this->Cron->save($this->request->data)) {
                $this->Session->setFlash('Your cron entry has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your cron entry.', 'flash_error');
            }
        } 
	}

	public function admin_edit($id = null)
	{
      	$this->Cron->id = $id;

	    if ($this->request->is('get')) {
	        $this->request->data = $this->Cron->read();
	    } else {
        	$amount = $this->request->data['Cron']['period_amount'];
        	$type = $this->request->data['Cron']['period_type'];
        	$this->request->data['Cron']['run_time'] = date('Y-m-d H:i:s', strtotime('+' . $amount . ' '.$type));
        	
	        if ($this->Cron->save($this->request->data)) {
	            $this->Session->setFlash('Your cron entry has been updated.', 'flash_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your cron entry.', 'flash_error');
	        }
	    }
	}

	public function admin_delete($id = null, $title = null, $permanent = null)
	{
		if ($this->request->is('post')) {
	        throw new MethodNotAllowedException();
	    }

	    $this->Cron->id = $id;

	    if (!empty($permanent)) {
	    	$delete = $this->Cron->delete($id);
	    } else {
	    	$delete = $this->Cron->saveField('deleted_time', $this->Cron->dateTime());
	    }

	    if ($delete) {
	        $this->Session->setFlash('The cron entry `'.$title.'` has been deleted.', 'flash_success');
	    } else {
	    	$this->Session->setFlash('The cron entry `'.$title.'` has NOT been deleted.', 'flash_error');
	    }
	    
	    if (!empty($permanent)) {
	    	$this->redirect(array('action' => 'index', 'trash' => 1));
	    } else {
	    	$this->redirect(array('action' => 'index'));
	    }
	}
}