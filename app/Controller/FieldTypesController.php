<?php
App::uses('AppController', 'Controller');

/**
 * Class FieldTypesController
 * @property FieldType $FieldType
 */
class FieldTypesController extends AppController
{
    /**
    * Name of the Controller, 'FieldTypes'
    */
	public $name = 'FieldTypes';

    /**
    * array of permissions for this page
    */
	private $permissions;

    /**
    * In this beforeFilter we will get the permissions to be used in the view files
    *
    * We also get a modules list that will be used on edit and add, along with period amount.
    */
	public function beforeFilter()
	{
		parent::beforeFilter();

		$this->permissions = $this->getPermissions();
	}

    /**
    * Returns a paginated index of FieldTypes
    *
    * @return array of block data
    */
    public function admin_index()
    {
        $conditions = array();

        if ($this->permissions['any'] == 0)
            $conditions['User.id'] = $this->Auth->user('id');

        if (isset($this->request->named['trash']))
            $conditions['FieldType.only_deleted'] = true;

        $this->Paginator->settings = array(
            'conditions' => array(
                $conditions
            ),
            'contain' => array(
                'User'
            )
        );
        
        $this->request->data = $this->Paginator->paginate('FieldType');
    }

    /**
    * Returns nothing before post
    *
    * On POST, returns error flash or success flash and redirect to index on success
    *
    * @return mixed
    */
    public function admin_add()
    {
        if (!empty($this->request->data))
        {
	        $this->FieldType->create();

            $this->request->data['FieldType']['user_id'] = $this->Auth->user('id');

            if ($this->FieldType->save($this->request->data))
            {
                $this->Session->setFlash('Your field type has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your field type.', 'flash_error');
            }
        } 
    }

    /**
     * Before POST, sets request data to form
     *
     * After POST, flash error or flash success and redirect to index
     *
     * @param int|null $id
     * @internal param int $id of the database entry
     * @return array of field type data
     */
    public function admin_edit($id)
    {
        $this->FieldType->id = $id;

        if (!empty($this->request->data))
        {           
            $this->request->data['FieldType']['user_id'] = $this->Auth->user('id');
            
            if ($this->FieldType->save($this->request->data))
            {
                $this->Session->setFlash('Your field type has been updated.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to update your field type.', 'flash_error');
            }
        }

        $this->request->data = $this->FieldType->read();
	    $this->hasAccessToItem($this->request->data);

        $path = VIEW_PATH . 'Elements' . DS . 'FieldTypes' . DS . $this->request->data['FieldType']['slug'] . '.ctp';
        $data_path = VIEW_PATH . 'Elements' . DS . 'FieldTypesData' . DS . $this->request->data['FieldType']['slug'] . '.ctp';

        if (file_exists($path))
             $this->request->data['FieldType']['template'] = file_get_contents($path);

        if (file_exists($data_path))
            $this->request->data['FieldType']['data_template'] = file_get_contents($data_path);
    }

    /**
     * If item has no delete time, then initial deletion is to the trash area (making it in-active on site, if applicable)
     *
     * But if it has a deletion time, meaning it is in the trash, deleting it the second time is permanent.
     *
     * @param int|null $id
     * @param null|string $title
     * @return void
     */
    public function admin_delete($id, $title = null)
    {
        $this->FieldType->id = $id;

	    $data = $this->FieldType->findById($id);
	    $this->hasAccessToItem($data);

	    $permanent = $this->FieldType->remove($data);

	    $this->Session->setFlash('The field type `'.$title.'` has been deleted.', 'flash_success');

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
     * @param int|null $id
     * @param null|string $title
     * @return void
     */
    public function admin_restore($id, $title = null)
    {
        $this->FieldType->id = $id;

        if ($this->FieldType->restore())
        {
            $this->Session->setFlash('The field type `'.$title.'` has been restored.', 'flash_success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The field type `'.$title.'` has NOT been restored.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }
    }
}