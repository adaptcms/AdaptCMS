<?php
App::uses('AppController', 'Controller');

/**
 * Class FilesController
 *
 * @property File $File
 */
class FilesController extends AppController
{
    /**
    * Name of the Controller, 'Files'
    */
	public $name = 'Files';

    /**
    * array of permissions for this page
    */
	private $permissions;

    /**
    * In this beforeFilter we will get the permissions to be used in the view files
    */
	public function beforeFilter()
	{
		parent::beforeFilter();

		$this->permissions = $this->getPermissions();

		if ($this->request->action == 'admin_add' || $this->request->action == 'admin_edit' || strstr($this->request->action, 'add'))
		{
			$this->loadModel('Theme');

			$file_types = array_combine($this->Theme->file_types_editable, $this->Theme->file_types_editable);

			$media_list = $this->File->Media->find('list');
			$zoom_levels = $this->File->getZoomLevels();

            $this->set(compact('file_types', 'media_list', 'zoom_levels'));
		}

        if ($this->request->action == 'admin_add')
            $this->Security->validatePost = false;
	}

    /**
     * Admin Index
     * Returns a paginated index of Files
     *
     * @return array
     */
	public function admin_index()
	{
		$conditions = array();

		if (isset($this->request->named['trash']))
	        $conditions['File.only_deleted'] = true;

	    if ($this->permissions['any'] == 0)
	    	$conditions['User.id'] = $this->Auth->user('id');

        $this->Paginator->settings = array(
            'conditions' => $conditions,
            'contain' => array(
            	'User'
            )
        );
        
        $this->request->data = $this->Paginator->paginate('File');
	}

	/**
	 * Admin Add
	 *
	 * @return void
	 */
	public function admin_add()
	{
        if (!empty($this->request->data))
        {
	        $this->File->create();

        	$this->request->data['File']['user_id'] = $this->Auth->user('id');

            if ($this->File->saveAll( $this->File->beforeAdd($this->request->data) ))
            {
                $this->Session->setFlash('Your file has been upload.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to upload your file.', 'flash_error');
            }
        }
	}

	/**
	 * Admin Add Folder
	 * Method that allows user to upload a folder of files to import.
	 *
	 * @return void
	 */
	public function admin_add_folder()
	{
		if (!empty($this->request->data))
		{
			$files = $this->File->uploadFolder($this->Auth->user('id'), $this->request->data['File']);

			if ($this->File->saveAll( $files ))
			{
				$this->Session->setFlash('Your folder of files has been imported.', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to import your folder of files.', 'flash_error');
			}
		}

		// Gets list of folders that are eligible (writable, in specified location)
		$folders = $this->File->getFolders();
		$this->set(compact('folders'));
	}

    /**
     * Admin Edit
    * Before POST, sets request data to form
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param integer $id id of the database entry, redirect to index if no permissions
    * @return array
    */
	public function admin_edit($id)
	{
		$this->File->id = $id;

        if (!empty($this->request->data))
        {
            $this->request->data['File']['user_id'] = $this->Auth->user('id');

            if ($this->File->saveAll($this->request->data))
            {
                $this->Session->setFlash('Your file has been updated.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to update your file.', 'flash_error');
            }
        }

        $data = $this->File->find('first', array(
        	'conditions' => array(
        		'File.id' => $id
        	),
        	'contain' => array(
        		'Media',
        		'User'
        	)
        ));
		$this->hasAccessToItem($data);

        $file = WWW_ROOT.
        		$data['File']['dir'].
        		$data['File']['filename'];

        if (strstr($data['File']['mimetype'], 'image'))
        {
            $data['info'] = getimagesize($file);
        }
        else
        {
            $this->set('file_contents', file_get_contents($file));
        }

		$this->request->data = $data;
	}

    /**
     * Admin Delete
     * If item has no delete time, then initial deletion is to the trash area (making it in-active on site, if applicable)
     *
     * But if it has a deletion time, meaning it is in the trash, deleting it the second time is permanent.
     *
     * @param integer $id id of the database entry, redirect to index if no permissions
     * @return void
     */
    public function admin_delete($id)
    {
        $this->File->id = $id;

	    $data = $this->File->findById($id);
	    $this->hasAccessToItem($data);

	    $permanent = $this->File->remove($data);

	    $this->Session->setFlash('The file `' . $data['File']['filename'] . '` has been deleted.', 'flash_success');

	    if ($permanent)
	    {
		    $this->redirect(array('action' => 'index', 'trash' => 1));
	    } else {
		    $this->redirect(array('action' => 'index'));
	    }
    }

    /**
     * Admin Restore
     * Restoring an item will take an item in the trash and reset the delete time
     *
     * This makes it live wherever applicable
     *
     * @param integer $id ID of database entry, redirect if no permissions
     * @return void
     */
    public function admin_restore($id)
    {
        $this->File->id = $id;

	    $data = $this->File->findById($id);
	    $this->hasAccessToItem($data);

        if ($this->File->restore())
        {
            $this->Session->setFlash('The file `' . $data['File']['filename'] . '` has been restored.', 'flash_success');
        } else {
            $this->Session->setFlash('The file `' . $data['File']['filename'] . '` has NOT been restored.', 'flash_error');
        }

        $this->redirect(array('action' => 'index'));
    }
}