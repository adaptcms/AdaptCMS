<?php

class FieldsController extends AppController {
	public $name = 'Fields';

	public function admin_index()
	{
        if (!isset($this->params->named['trash'])) {
            $this->paginate = array(
                'order' => 'Field.created DESC',
                'limit' => $this->pageLimit,
                'conditions' => array(
                    'Field.deleted_time' => '0000-00-00 00:00:00'
                ),
                'contain' => array(
                    'Category'
                ),
                'fields' => 'Field.*, Category.*'
            );
        } else {
            $this->paginate = array(
                'order' => 'Field.created DESC',
                'limit' => $this->pageLimit,
                'conditions' => array(
                    'Field.deleted_time' => '0000-00-00 00:00:00'
                ),
                'contain' => array(
                    'Category'
                ),
                'fields' => 'Field.*, Category.*'
            );
        }
        
        $this->request->data = $this->paginate('Field');
	}

	public function admin_add()
	{
		$categories = $this->Field->Category->find('list', array(
            'conditions' => array(
                'Category.deleted_time' => '0000-00-00 00:00:00'
            )
        ));

        /*
        $fields = $this->Field->find('all');

        $import = array();
        foreach($fields as $key => $field) {
            $import[$field['Field']['id']] = $field['Field']['id'];
        }
        */
        $import = $this->Field->find('list');

        $this->set(compact('import', 'categories'));

        if ($this->request->is('post')) {
            if ($this->request->data['Field']['required'] == 1) {
                    $rules[] = "required: true,";
            } else {
                    $rules[] = "required: false,";
            }
            if ($this->request->data['Field']['field_limit_min'] > 0) {
                    $rules[] = "minlength: ".$this->request->data['Field']['field_limit_min'].",";
            }
            if ($this->request->data['Field']['field_limit_max'] > 0) {
                    $rules[] = "maxlength: ".$this->request->data['Field']['field_limit_max'].",";
            }
            if ($this->request->data['Field']['field_type'] == "email") {
                    $rules[] = "email: true,";
            }
            if ($this->request->data['Field']['field_type'] == "url") {
                    $rules[] = "url: true,";
            }
            if ($this->request->data['Field']['field_type'] == "num") {
                    $rules[] = "number: true,";
            }

            $this->request->data['Field']['rules'] = json_encode($rules);
            unset($rules);

            if (empty($this->request->data['Field']['label'])) {
                    $this->request->data['Field']['label'] = $this->request->data['Field']['title'];
            }

    		$this->request->data['Field']['title'] = $this->slug($this->request->data['Field']['title']);
            if (!empty($this->request->data['FieldData'])) {
                $this->request->data['Field']['field_options'] = 
                    json_encode($this->request->data['FieldData']);
            }

            if ($this->Field->save($this->request->data)) {
                $this->Session->setFlash('Your field has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your field.', 'flash_error');
            }
        } 
	}

	public function admin_edit($id = null)
	{
	    if (!empty($this->request->data)) {
            if ($this->request->data['Field']['required'] == 1) {
                    $rules[] = "required: true,";
            } else {
                    $rules[] = "required: false,";
            }
            if ($this->request->data['Field']['field_limit_min'] > 0) {
                    $rules[] = "minlength: ".$this->request->data['Field']['field_limit_min'].",";
            }
            if ($this->request->data['Field']['field_limit_max'] > 0) {
                    $rules[] = "maxlength: ".$this->request->data['Field']['field_limit_max'].",";
            }
            if ($this->request->data['Field']['field_type'] == "email") {
                    $rules[] = "email: true,";
            }
            if ($this->request->data['Field']['field_type'] == "url") {
                    $rules[] = "url: true,";
            }
            if ($this->request->data['Field']['field_type'] == "num") {
                    $rules[] = "number: true,";
            }

            $this->request->data['Field']['rules'] = json_encode($rules);
            unset($rules);
            
            if (empty($this->request->data['Field']['label'])) {
                    $this->request->data['Field']['label'] = $this->request->data['Field']['title'];
            }
    		$this->request->data['Field']['title'] = $this->slug($this->request->data['Field']['title']);
            if (!empty($this->request->data['FieldData'])) {
                $this->request->data['Field']['field_options'] = 
                    json_encode($this->request->data['FieldData']);
            }

	        if ($this->Field->save($this->request->data)) {
	            $this->Session->setFlash('Your field has been updated.', 'flash_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your field.', 'flash_error');
	        }
	    }

        $this->request->data = $this->Field->findById($id);

        $categories = $this->Field->Category->find('list', array(
            'conditions' => array(
                'Category.deleted_time' => '0000-00-00 00:00:00'
            )
        ));

        $fields = $this->Field->find('all', array(
            'conditions' => array(
                'Field.category_id' => $this->request->data['Field']['category_id']
            ),
            'order' => 'Field.field_order ASC'
        ));

        $this->set(compact('categories', 'fields'));
	}

	public function admin_delete($id = null, $title = null, $permanent = null)
	{
        if ($this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $this->Field->id = $id;

        if (!empty($permanent)) {
            $delete = $this->Field->delete($id);
        } else {
            $delete = $this->Field->saveField('deleted_time', $this->Field->dateTime());
        }

        if ($delete) {
	        $this->Session->setFlash('The field `'.$title.'` has been deleted.', 'flash_success');
	    } else {
	    	$this->Session->setFlash('The field `'.$title.'` has NOT been deleted.', 'flash_error');
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

        $this->Field->id = $id;

        if ($this->Field->saveField('deleted_time', '0000-00-00 00:00:00')) {
            $this->Session->setFlash('The field `'.$title.'` has been restored.', 'flash_success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The field `'.$title.'` has NOT been restored.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }
    }

    public function admin_ajax_order()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;

        $data = array();
        $i = 0;

        foreach($this->request->data['Field']['field_ids'] as $key => $field) {
            if (!empty($field) && $field > 0) {
                $data[$i]['id'] = $field;
                $data[$i]['field_order'] = $key;
                
                $i++;
            }
        }

        if ($this->Field->saveAll($data)) {
            return json_encode(array(
                'status' => true,
                'message' => '
                    <div id="flashMessage" class="alert alert-success">
                        <button class="close" data-dismiss="alert" style="margin-top: 0;float: right">×</button>
                        <strong>Success</strong> Field order has been saved.
                    </div>'
            ));
        } else {
            return json_encode(array(
                'status' => false,
                'message' => '
                    <div id="flashMessage" class="alert alert-error">
                        <button class="close" data-dismiss="alert" style="margin-top: 0;float: right">×</button>
                        <strong>Error</strong> Field order could not be saved, please try again.
                    </div>'
            ));
        }
    }

    public function admin_ajax_fields()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;

        $fields = $this->Field->find('all', array(
            'conditions' => array(
                'Field.category_id' => $this->request->data['Field']['category_id']
            ),
            'order' => 'Field.field_order ASC'
        ));

        $original = "<span>".$this->request->data['Field']['title']."</span> <i class='icon icon-question-sign' 
            data-content='".$this->request->data['Field']['description']."' 
            data-title='".$this->request->data['Field']['title']."'></i> 

            <span class='label label-info pull-right'>
                Current Field
            </span>";

        foreach($fields as $key => $field) {
            $data .= "<li class='btn' id='".$field['Field']['id']."'><i class='icon icon-move'></i> ";

            if ($field['Field']['id'] == $this->request->data['Field']['id']) {
                $data .= $original;
                $current = 1;
            } else {
                $data .= "<span>".$field['Field']['label']."</span> 
                <i class='icon icon-question-sign' data-content='".$field['Field']['description']."' data-title='".$field['Field']['label']."'></i>";
            }

            $data .= "</li>
            ";
        }

        if (empty($current)) {
            $key = count($data);
            if (empty($this->request->data['Field']['id'])) {
                $this->request->data['Field']['id'] = 0;
            }

            $data .= "<li class='btn' id='".$this->request->data['Field']['id']."'><i class='icon icon-move'></i> ".$original." </li>
            ";
        }

        return json_encode(array(
            'status' => true,
            'data' => $data
        ));
    }

    public function admin_ajax_import()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;

        $data = $this->Field->findById(
            $this->request->data['Field']['id']
        );

        return json_encode(array(
            'data' => $data
        ));
    }
}