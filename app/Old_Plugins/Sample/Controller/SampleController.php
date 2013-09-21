<?php
App::uses('AppController', 'Controller');

/**
 * Class Sample itemsController
 * @property Sample $Sample
 */
class SampleController extends AppController
{
    /**
     * Name of the Controller, 'Sample'
     */
    public $name = 'Sample';

    public $uses = array('Sample.Sample');

    /**
     * array of permissions for this page
     */
    private $permissions;

    /**
     * In this beforeFilter we get the permissions
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->permissions = $this->getPermissions();
    }

    /**
     * Returns a paginated index of Sample items
     *
     * @return array of block data
     */
    public function admin_index()
    {
        $conditions = array();

        if ($this->permissions['any'] == 0)
        {
            $conditions['User.id'] = $this->Auth->user('id');
        }

        if (!isset($this->request->named['trash'])) {
            $conditions['Sample.deleted_time'] = '0000-00-00 00:00:00';
        } else {
            $conditions['Sample.deleted_time !='] = '0000-00-00 00:00:00';
        }

        $this->Paginator->settings = array(
            'order' => 'Sample.created DESC',
            'conditions' => array(
                $conditions
            ),
            'contain' => array(
                'User'
            )
        );

        $this->request->data = $this->Paginator->paginate('Sample');
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
            $this->request->data['Sample']['user_id'] = $this->Auth->user('id');

            if ($this->Sample->save($this->request->data))
            {
                $this->Session->setFlash('Your Sample item has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your Sample item.', 'flash_error');
            }
        }
    }

    /**
     * Before POST, sets request data to form
     *
     * After POST, flash error or flash success and redirect to index
     *
     * @param int - ID of the database entry
     * @return array of Sample data
     */
    public function admin_edit($id)
    {
        $this->Sample->id = $id;

        if (!empty($this->request->data))
        {
            $this->request->data['Sample']['user_id'] = $this->Auth->user('id');

            if ($this->Sample->save($this->request->data))
            {
                $this->Session->setFlash('Your Sample item has been updated.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to update your Sample item.', 'flash_error');
            }
        }

        $this->request->data = $this->Sample->read();
    }

    /**
     * If item has no delete time, then initial deletion is to the trash area (making it in-active on site, if applicable)
     *
     * But if it has a deletion time, meaning it is in the trash, deleting it the second time is permanent.
     *
     * @param int - ID of the database entry, redirect to index if no permissions
     * @param string - Title of this entry, used for flash message
     * @param boolean $permanent
     * @internal param \If $permanent not NULL, this means the item is in the trash so deletion will now be permanent
     * @return redirect
     */
    public function admin_delete($id, $title = null, $permanent = null)
    {
        $this->Sample->id = $id;

        if (!empty($permanent))
        {
            $delete = $this->Sample->delete($id);
        } else {
            $delete = $this->Sample->saveField('deleted_time', $this->Sample->dateTime());
        }

        if ($delete)
        {
            $this->Session->setFlash('The Sample item `'.$title.'` has been deleted.', 'flash_success');
        } else {
            $this->Session->setFlash('The Sample item `'.$title.'` has NOT been deleted.', 'flash_error');
        }

        if (!empty($permanent))
        {
            $count = $this->Sample->find('count', array(
                'conditions' => array(
                    'Sample.deleted_time !=' => '0000-00-00 00:00:00'
                )
            ));

            $params = array('action' => 'index');

            if ($count > 0)
            {
                $params['trash'] = 1;
            }

            $this->redirect($params);
        } else {
            $this->redirect(array('action' => 'index'));
        }
    }

    /**
     * Restoring an item will take an item in the trash and reset the delete time
     *
     * This makes it live wherever applicable
     *
     * @param int - ID of database entry, redirect if no permissions
     * @param string - Title of this entry, used for flash message
     * @return redirect
     */
    public function admin_restore($id, $title = null)
    {
        $this->Sample->id = $id;

        if ($this->Sample->saveField('deleted_time', '0000-00-00 00:00:00'))
        {
            $this->Session->setFlash('The Sample item `'.$title.'` has been restored.', 'flash_success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The Sample item `'.$title.'` has NOT been restored.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }
    }

	/**
	 * Index Method
	 * Returns a paginated list of sample items
	 *
	 * @return void
	 */
	public function index()
	{
		$conditions = array();

		$conditions['Sample.deleted_time'] = '0000-00-00 00:00:00';

		if ($this->permissions['any'] == 0)
		{
			$conditions['User.id'] = $this->Auth->user('id');
		}

		$this->Paginator->settings = array(
			'order' => 'Sample.created DESC',
			'conditions' => $conditions,
			'contain' => array(
				'User'
			)
		);

		$this->request->data = $this->Paginator->paginate('Sample');
	}

	/**
	 * View Method
	 * Returns a data array if it finds the sample by the supplied slug
	 *
	 * @param string $slug
	 *
	 * @return void
	 */
	public function view($slug)
	{
		$this->request->data = $this->Sample->find('first', array(
			'conditions' => array(
				'Sample.slug' => $slug
			),
			'contain' => array(
				'User'
			)
		));
	}
}