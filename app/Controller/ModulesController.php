<?php

class ModulesController extends AppController
{
	public $name = 'Modules';

	public function admin_index()
	{
		if (!isset($this->params->named['trash'])) {
	        $this->paginate = array(
	            'order' => 'Module.created DESC',
	            'limit' => $this->pageLimit,
	            'conditions' => array(
	            	'Module.deleted_time' => '0000-00-00 00:00:00'
	            )
	        );
        } else {
	        $this->paginate = array(
	            'order' => 'Module.created DESC',
	            'limit' => $this->pageLimit,
	            'conditions' => array(
	            	'Module.deleted_time !=' => '0000-00-00 00:00:00'
	            )
	        );
        }
        
        $this->request->data = $this->paginate('Module');

        if ($this->Session->check('Module.1.name')) {
        	$this->set('action', 'step_two');
	    } else {
	        $this->set('action', 'add');
	    }
	}

	public function admin_add()
	{
		$this->loadModel('ComponentModel');
		$model_list = $this->ComponentModel->find('all', array(
			'conditions' => array(
				'ComponentModel.module_active' => 1
				)
			)
		);

		foreach ($model_list as $row) {
			if ($row['ComponentModel']['is_plugin'] == 1) {
				$models["Plugin".$row['ComponentModel']['model_title']] = "Plugin - ".$row['ComponentModel']['title'];
			} else {
				$models[$row['ComponentModel']['model_title']] = $row['ComponentModel']['title'];
			}
		}

		if ($this->Session->check('Module.1.name')) {
	        $this->request->data['Module']['model'] = $this->Session->read('Module.1.name');
	    }

		$this->set(compact('models'));
	}

	public function admin_step_two()
	{
		if ($this->Session->check('Module.2')) {
			$this->redirect(array('action' => 'step_three'));
		}

		if (!empty($this->request->data['Module']['model'])) {
			$model = $this->request->data['Module']['model'];

			if (!$this->Session->check('Module.1.name')) {

				$array["name"] = $model;
				$this->Session->write('Module.1', $array);
			}
		}

		if ($this->Session->check('Module.1')) {
			$model = $this->Session->read('Module.1.name');

			$this->loadModel($model);
			$this->loadModel('ComponentModel');

			$model_title = $this->ComponentModel->findByModelTitle(str_replace("Plugin","",$model));

			$this->set('model_title', $model_title['ComponentModel']['title']);
			$this->set('list', $this->$model->find('list'));
		}

		if (empty($model)) {
			$this->redirect(array('action' => 'add'));
		}

		$this->loadModel('Theme');

		$this->set('themes', $this->Theme->find('list', array(
                'order' => 'Theme.id ASC',
                'conditions' => array(
                	'Theme.deleted_time' => '0000-00-00 00:00:00'
                	)
                )
        ));

        for ($i = 0; $i <= 100; $i++) {
        	$limit[] = $i;
        }

        $this->set(compact('limit'));
	}

	public function admin_step_three()
	{

	}

	public function admin_edit($id = null)
	{

	}

	public function admin_delete($id = null, $title = null, $permanent = null)
	{
        if ($this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $this->Module->id = $id;

        if (!empty($permanent)) {
            $delete = $this->Module->delete($id);
        } else {
            $delete = $this->Module->saveField('deleted_time', $this->Module->dateTime());
        }

        if ($delete) {
	        $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> The module `'.$title.'` has been deleted.', 'default', array('class' => 'alert alert-success'));
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> The module `'.$title.'` has NOT been deleted.', 'default', array('class' => 'alert alert-error'));
	        $this->redirect(array('action' => 'index'));
	    }
	}

    public function admin_restore($id = null, $title = null)
    {
        if ($this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $this->Module->id = $id;

        if ($this->Module->saveField('deleted_time', '0000-00-00 00:00:00')) {
            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> The module `'.$title.'` has been restored.', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> The module `'.$title.'` has NOT been restored.', 'default', array('class' => 'alert alert-error'));
            $this->redirect(array('action' => 'index'));
        }
    }
}