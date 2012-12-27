<?php

class ModulesController extends AppController
{
	public $name = 'Modules';

	public function beforeFilter()
	{
		parent::beforeFilter();

		if ($this->params->action == 'admin_edit' || $this->params->action == 'admin_add') {
			$model_list = $this->Module->Components->find('all', array(
				'conditions' => array(
					'Components.module_active' => 1
					)
				)
			);

			foreach ($model_list as $row) {
				if ($row['Components']['is_plugin'] == 1) {
					$models[$row['Components']['title'].'.'.$row['Components']['model_title']] = "Plugin - ".$row['Components']['title'];
				} else {
					$models[$row['Components']['model_title']] = $row['Components']['title'];
				}
			}

			$this->set(compact('models'));

			$this->loadModel('Theme');
			$this->set('themes', $this->Theme->find('list', array(
	                'order' => 'Theme.id ASC',
	                'conditions' => array(
	                	'Theme.deleted_time' => '0000-00-00 00:00:00'
	            	)
	            )
	        ));

	        for ($i = 1; $i <= 50; $i++) {
	        	$limit[$i] = $i;
	        }

	        $this->set(compact('limit'));
		}
	}

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
        if (!empty($this->request->data)) {
        	$this->Module->create();

            if ($this->Module->save($this->Module->filterdata($this->request->data))) {
                $this->Session->setFlash('Your module has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add module.', 'flash_error');
            }
        }
	}

	public function admin_edit($id = null)
	{
      	$this->Module->id = $id;

	    if ($this->request->is('get')) {
	        $this->request->data = $this->Module->preFilterData($this->Module->find('first', array(
	        	'conditions' => array(
	        		'Module.id' => $id
	        	),
	        	'contain' => array(
	        		'Components',
	        		'Template'
	        	)
	        )));
	    } else {
	        if ($this->Module->save($this->Module->filterdata($this->request->data))) {
	            $this->Session->setFlash('Your Module has been updated.', 'flash_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your Module.', 'flash_error');
	        }
	    }
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
	        $this->Session->setFlash('The module `'.$title.'` has been deleted.', 'flash_success');
	    } else {
	    	$this->Session->setFlash('The module `'.$title.'` has NOT been deleted.', 'flash_error');
	    }

	    if (!empty($permanent)) {
	    	$this->redirect(array('action' => 'index', 'trash' => 1));
	    } else {
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
            $this->Session->setFlash('The module `'.$title.'` has been restored.', 'flash_success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The module `'.$title.'` has NOT been restored.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }
    }

    public function admin_ajax_get_model()
    {
    	$this->layout = 'ajax';
    	$this->autoRender = false;

    	if ($this->request->data['Module']['type'] == "action") {
    		$this->loadModel('Components');
    		$find = $this->Components->findByTitle($this->request->data['Module']['component_id']);
    		$model = $find['Components']['model_title'];

	        if ($find['Components']['is_plugin'] == 1) {
	            $model = $find['Components']['model_title'];
	            $this->loadModel(
	                str_replace(' ','',$find['Components']['title']).'.'.$model
	            );
	        } else {
	            $model = $find['Components']['model_title'];
	            $this->loadModel($model);
	        }

	        $data = $this->$model->find('all');
    	} else {
    		$model = $this->request->data['Module']['component_id'];
    		$this->loadModel($model);

    		if (strstr($model, '.')) {
    			$ex = explode('.', $model);
    			$model_name = $ex[1];
    			$data = $this->$ex[1]->find('all');
			} else {
				$model_name = $model;
				$data = $this->$model->find('all');
			}
    	}

    	$list_data = array();

    	foreach($data as $row) {
    		if (!empty($row[$model_name]['slug'])) {
    			$list_data[$row[$model_name]['slug']] = $row[$model_name]['title'];
			} else {
				$list_data[$row[$model_name]['id']] = $row[$model_name]['title'];
			}
    	}

    	return json_encode($list_data);
    }
}