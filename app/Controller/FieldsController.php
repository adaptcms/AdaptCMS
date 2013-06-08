<?php

class FieldsController extends AppController
{
    /**
    * Name of the Controller, 'Fields'
    */
	public $name = 'Fields';

    /**
    * array of permissions for this page
    */
    private $permissions;

    /**
    * In this beforeFilter we will get the permissions to be used in the view files
    * If this is an admin action, an array of categories and field types are passed to the view as well.
    */
    public function beforeFilter()
    {
        parent::beforeFilter();

        $actions = array(
            'admin_index',
            'admin_add',
            'admin_edit'
        );

        if (in_array($this->params->action, $actions))
        {
            $categories = $this->Field->Category->find('list', array(
                'conditions' => array(
                    'Category.deleted_time' => '0000-00-00 00:00:00'
                )
            ));

            $modules = $this->Field->Module->find('list', array(
                'conditions' => array(
                    'Module.is_fields' => 1
                )
            ));

            if ($this->params->action != 'admin_index')
            {
                foreach($modules as $key => $row)
                {
                    $categories['Modules']['module_' . $key] = $row;
                }
            }
            else
            {
                $this->set(compact('modules'));
            }

            $types = $this->Field->FieldType->getList();

            $this->set('field_types', $types['list']);
            $this->set('field_rules', $types['rules']);

            $this->set(compact('categories'));
        }

        $this->permissions = $this->getPermissions();
    }

    /**
    * Returns a paginated index of Fields
    *
    * @return associative array of fields data
    */
	public function admin_index()
	{
        $conditions = array();

        if (!empty($this->params->named['category_id']))
        {
            $conditions['Category.id'] = $this->params->named['category_id'];
        }

        if (!empty($this->params->named['module_id']))
        {
            $conditions['Module.id'] = $this->params->named['module_id'];
        }

        if (isset($this->params->named['field_type']))
        {
            $conditions['Field.field_type'] = $this->params->named['field_type'];
        }

        if ($this->permissions['any'] == 0)
        {
            $conditions['User.id'] = $this->Auth->user('id');
        }

        if (!isset($this->params->named['trash'])) {
            $conditions['Field.deleted_time'] = '0000-00-00 00:00:00';
        } else {
            $conditions['Field.deleted_time !='] = '0000-00-00 00:00:00';
        }

        $this->paginate = array(
            'order' => 'Field.created DESC',
            'limit' => $this->pageLimit,
            'conditions' => array(
                $conditions
            ),
            'contain' => array(
                'Category',
                'Module',
                'User',
                'FieldType'
            ),
            'fields' => 'Field.*,FieldType.*,Category.*,Module.*,User.*'
        );
        
        $this->request->data = $this->paginate('Field');
	}

    /**
    * Returns nothing before post
    *
    * On POST, returns error flash or success flash and redirect to index on success
    * A list of fields is passed to the view for  
    *
    * @return mixed
    */
	public function admin_add()
	{
        $import = $this->Field->find('list');

        $this->set(compact('import'));

        if (!empty($this->request->data))
        {
            $this->request->data['Field']['user_id'] = $this->Auth->user('id');

            if ($this->Field->save($this->request->data))
            {
                $this->Session->setFlash('Your field has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your field.', 'flash_error');
            }
        } 
	}

    /**
    * Before POST, sets request data to form
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param id ID of the database entry, redirect to index if no permissions
    * @return associative array of field data
    */
	public function admin_edit($id = null)
	{
	    if (!empty($this->request->data))
        {
            $this->request->data['Field']['user_id'] = $this->Auth->user('id');

	        if ($this->Field->save($this->request->data))
            {
	            $this->Session->setFlash('Your field has been updated.', 'flash_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your field.', 'flash_error');
	        }
	    }

        $this->request->data = $this->Field->find('first', array(
            'conditions' => array(
                'Field.id' => $id
            ),
            'contain' => array(
                'User'
            )
        ));

        if ($this->request->data['User']['id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect(array('action' => 'index'));                
        }

        if ($this->request->data['Field']['category_id'] > 0)
        {
            $conditions['Field.category_id'] = $this->request->data['Field']['category_id'];
        }
        else
        {
            $conditions['Field.module_id'] = $this->request->data['Field']['module_id'];
        }

        $fields = $this->Field->find('all', array(
            'conditions' => $conditions,
            'order' => 'Field.field_order ASC'
        ));

        $this->set(compact('fields'));
	}

    /**
    * If item has no delete time, then initial deletion is to the trash area (making it in-active on site, if applicable)
    *
    * But if it has a deletion time, meaning it is in the trash, deleting it the second time is permanent.
    *
    * @param id ID of the database entry, redirect to index if no permissions
    * @param title Title of this entry, used for flash message
    * @param permanent If not NULL, this means the item is in the trash so deletion will now be permanent
    * @return redirect
    */
	public function admin_delete($id = null, $title = null, $permanent = null)
	{
        $this->Field->id = $id;

        $data = $this->Field->find('first', array(
            'conditions' => array(
                'Field.id' => $id
            ),
            'contain' => array(
                'User'
            )
        ));
        if ($data['User']['id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect(array('action' => 'index'));                
        }

        if (!empty($permanent))
        {
            $delete = $this->Field->delete($id);
        } else {
            $delete = $this->Field->saveField('deleted_time', $this->Field->dateTime());
        }

        if ($delete)
        {
	        $this->Session->setFlash('The field `'.$title.'` has been deleted.', 'flash_success');
	    } else {
	    	$this->Session->setFlash('The field `'.$title.'` has NOT been deleted.', 'flash_error');
	    }

        if (!empty($permanent))
        {
            $this->redirect(array('action' => 'index', 'trash' => 1));
        } else {
            $this->redirect(array('action' => 'index'));
        }
	}

    /**
    * Restoring an item will take an item in the trash and reset the delete time
    *
    * This makes it live wherever applicable
    *
    * @param id ID of database entry, redirect if no permissions
    * @param title Title of this entry, used for flash message
    * @return redirect
    */
    public function admin_restore($id = null, $title = null)
    {
        $this->Field->id = $id;

        $data = $this->Field->find('first', array(
            'conditions' => array(
                'Field.id' => $id
            ),
            'contain' => array(
                'User'
            )
        ));
        if ($data['User']['id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect(array('action' => 'index'));                
        }

        if ($this->Field->saveField('deleted_time', '0000-00-00 00:00:00')) {
            $this->Session->setFlash('The field `'.$title.'` has been restored.', 'flash_success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The field `'.$title.'` has NOT been restored.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }
    }

    /**
    * The second part of adjust field order, this is the function that saves the field order.
    *
    * @return json_encode array of status and flash message data
    */
    public function admin_ajax_order()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;

        $data = array();
        $i = 0;

        foreach($this->request->data['Field']['field_ids'] as $key => $field)
        {
            if (!empty($field) && $field > 0)
            {
                $data[$i]['id'] = $field;
                $data[$i]['field_order'] = $key;
                
                $i++;
            }
        }

        if (empty($data) || $this->Field->saveAll($data))
        {
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

    /**
    * The AJAX Fields function returns a list of fields, 
    * used when adding/editing a field to adjust field order. 
    *
    * @return json_encode array containg status of true and data
    */
    public function admin_ajax_fields()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;

        $conditions = array();

        if (is_numeric($this->request->data['Field']['category_id']))
        {
            $conditions['Field.category_id'] = $this->request->data['Field']['category_id'];
        }
        else
        {
            $conditions['Field.module_id'] = str_replace('module_', '', $this->request->data['Field']['category_id']);
        }

        $fields = $this->Field->find('all', array(
            'conditions' => $conditions,
            'order' => 'Field.field_order ASC'
        ));

        $original = "<span>".$this->request->data['Field']['title']."</span> <i class='icon icon-question-sign' 
            data-content='".$this->request->data['Field']['description']."' 
            data-title='".$this->request->data['Field']['title']."'></i> 

            <span class='label label-info pull-right'>
                Current Field
            </span>";

        foreach($fields as $key => $field)
        {
            $data .= "<li class='btn' id='".$field['Field']['id']."'><i class='icon icon-move'></i> ";

            if ($field['Field']['id'] == $this->request->data['Field']['id'])
            {
                $data .= $original;
                $current = 1;
            } else {
                $data .= "<span>".$field['Field']['label']."</span> 
                <i class='icon icon-question-sign' data-content='".$field['Field']['description']."' data-title='".$field['Field']['label']."'></i>";
            }

            $data .= "</li>
            ";
        }

        if (empty($current))
        {
            $key = count($data);
            if (empty($this->request->data['Field']['id']))
            {
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

    /**
    * This small function is used for importing fields
    *
    * @return json_encode array of field tata
    */
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