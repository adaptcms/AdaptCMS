<?php
App::uses('AppController', 'Controller');

/**
 * Class FieldsController
 *
 * @property Field $Field
 */
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

        if (strstr($this->request->action, 'admin_'))
        {
            $categories = $this->Field->Category->find('list');

            $modules = $this->Field->Module->find('list', array(
                'conditions' => array(
                    'Module.is_fields' => 1
                )
            ));

            if ($this->request->action != 'admin_index')
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
    * @return array Array of fields data
    */
	public function admin_index()
	{
        $conditions = array();

        if (!empty($this->request->named['category_id']))
            $conditions['Category.id'] = $this->request->named['category_id'];

        if (!empty($this->request->named['module_id']))
            $conditions['Module.id'] = $this->request->named['module_id'];

        if (isset($this->request->named['field_type']))
            $conditions['Field.field_type'] = $this->request->named['field_type'];

        if ($this->permissions['any'] == 0)
            $conditions['User.id'] = $this->Auth->user('id');

        if (isset($this->request->named['trash']))
            $conditions['Field.only_deleted'] = true;

        $this->Paginator->settings = array(
            'conditions' => $conditions,
            'contain' => array(
                'Category',
                'Module',
                'User',
                'FieldType'
            ),
            'fields' => 'Field.*,FieldType.*,Category.*,Module.*,User.*'
        );
        
        $this->request->data = $this->Paginator->paginate('Field');
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
	        $this->Field->saveFieldOrder($this->request->data);

	        $this->Field->create();

            $this->request->data['Field']['user_id'] = $this->Auth->user('id');

            if ($this->Field->save($this->request->data))
            {
                $this->Session->setFlash('Your field has been added.', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your field.', 'error');
            }
        } 
	}

    /**
    * Before POST, sets request data to form
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param integer $id id of the database entry, redirect to index if no permissions
    * @return array Array of field data
    */
	public function admin_edit($id)
	{
	    if (!empty($this->request->data))
        {
            $this->request->data['Field']['user_id'] = $this->Auth->user('id');

	        $this->Field->saveFieldOrder($this->request->data);

	        if ($this->Field->save($this->request->data))
            {
	            $this->Session->setFlash('Your field has been updated.', 'success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your field.', 'error');
	        }
	    }

        $this->request->data = $this->Field->findById($id);
		$this->hasAccessToItem($this->request->data);

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
    * @param integer $id id of the database entry, redirect to index if no permissions
    * @param string $title Title of this entry, used for flash message
    * @return mixed
    */
	public function admin_delete($id, $title = null)
	{
        $this->Field->id = $id;

        $data = $this->Field->findById($id);
		$this->hasAccessToItem($data);

        $permanent = $this->Field->remove($data);

        $this->Session->setFlash('The field `'.$title.'` has been deleted.', 'success');

        if ($permanent)
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
    * @param integer $id ID of database entry, redirect if no permissions
    * @param string $title Title of this entry, used for flash message
    * @return mixed
    */
    public function admin_restore($id, $title = null)
    {
        $this->Field->id = $id;

        $data = $this->Field->findById($id);
	    $this->hasAccessToItem($data);

        if ($this->Field->restore()) {
            $this->Session->setFlash('The field `'.$title.'` has been restored.', 'success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The field `'.$title.'` has NOT been restored.', 'error');
            $this->redirect(array('action' => 'index'));
        }
    }

    /**
    * The AJAX Fields function returns a list of fields, 
    * used when adding/editing a field to adjust field order. 
    *
    * @return string
    */
    public function admin_ajax_fields()
    {
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

	    return $this->_ajaxResponse('Fields/admin_ajax_fields', array(
		    'fields' => $fields,
		    'original' => $this->request->data['Field']
	    ));
    }

    /**
    * This small function is used for importing fields
    *
    * @return string
    */
    public function admin_ajax_import()
    {
        $data = $this->Field->findById(
            $this->request->data['Field']['id']
        );

	    return $this->_ajaxResponse(array('body' => $data));
    }
}