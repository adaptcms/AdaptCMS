<?php
App::uses('AppController', 'Controller');

/**
 * Class FilesController
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

		if ($this->params->action == 'admin_add' || $this->params->action == 'admin_edit')
		{
			$this->loadModel('Theme');

			$file_types = array_combine($this->Theme->file_types_editable, $this->Theme->file_types_editable);

            $this->set(compact('file_types'));
		}

        if ($this->params->action == 'admin_add')
            $this->Security->validatePost = false;
	}

    /**
    * Returns a paginated index of Files
    *
    * @return associative array of files data
    */
	public function admin_index()
	{
		$conditions = array();

		if (!isset($this->params->named['trash']))
		{
	        $conditions['File.deleted_time'] = '0000-00-00 00:00:00';
	    } else {
	        $conditions['File.deleted_time !='] = '0000-00-00 00:00:00';
        }

	    if ($this->permissions['any'] == 0)
	    {
	    	$conditions['User.id'] = $this->Auth->user('id');
	    }

        $this->paginate = array(
            'order' => 'File.created DESC',
            'limit' => $this->pageLimit,
            'conditions' => $conditions,
            'contain' => array(
            	'User'
            )
        );
        
        $this->request->data = $this->paginate('File');
	}

	public function admin_add($theme = null)
	{
        $media_list = $this->File->Media->find('list');

		$this->set(compact('media_list'));
		
        if (!empty($this->request->data))
        {
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
    * Before POST, sets request data to form
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param id ID of the database entry, redirect to index if no permissions
    * @return associative array of file data
    */
	public function admin_edit($id = null)
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

        if ($data['User']['id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect(array('action' => 'index'));	        	
        }

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

        $data['media-list'] = $this->File->Media->find('list');

	    $this->set('data', $data);
	}

    /**
    * If item has no delete time, then initial deletion is to the trash area (making it in-active on site, if applicable)
    *
    * But if it has a deletion time, meaning it is in the trash, deleting it the second time is permanent.
    *
    * @param id ID of the database entry, redirect to index if no permissions
    * @param permanent If not NULL, this means the item is in the trash so deletion will now be permanent
    * @return redirect
    */
    public function admin_delete($id = null, $permanent = null)
    {
        $this->File->id = $id;

        $file = $this->File->find('first', array(
            'conditions' => array(
                    'File.id' => $id
            ),
            'fields' => array(
                    'File.filename,File.dir,File.user_id'
            )
        ));
        
        if ($file['File']['user_id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect(array('action' => 'index'));	        	
        }

        if (!empty($permanent))
        {
            $delete = $this->File->delete($id);
            
            if (file_exists(WWW_ROOT . $file['File']['dir'] . $file['File']['filename']) && 
                is_readable(WWW_ROOT . $file['File']['dir'] . $file['File']['filename'])) {
                    unlink(WWW_ROOT.  $file['File']['dir'] . $file['File']['filename']);

                if (file_exists(WWW_ROOT . $file['File']['dir'] . 'thumb/' . $file['File']['filename']) && 
                    is_readable(WWW_ROOT . $file['File']['dir'] . 'thumb/' . $file['File']['filename'])) {
                    unlink(WWW_ROOT . $file['File']['dir'] . 'thumb/' . $file['File']['filename']);
                }
            }
        } else {
            $delete = $this->File->saveField('deleted_time', $this->File->dateTime());
        }

        if ($delete)
        {
            $this->Session->setFlash('The file `'.$file['File']['filename'].'` has been deleted.', 'flash_success');
        } else {
            $this->Session->setFlash('The file `'.$file['File']['filename'].'` has NOT been deleted.', 'flash_error');
        }

        if (!empty($permanent))
        {
            $count = $this->File->find('count', array(
                'conditions' => array(
                    'File.deleted_time !=' => '0000-00-00 00:00:00'
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
    * @param id ID of database entry, redirect if no permissions
    * @param title Title of this entry, used for flash message
    * @return redirect
    */
    public function admin_restore($id = null, $title = null)
    {
        $this->File->id = $id;

        $data = $this->File->find('first', array(
        	'conditions' => array(
        		'File.id' => $id
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

        if ($this->File->saveField('deleted_time', '0000-00-00 00:00:00'))
        {
            $this->Session->setFlash('The file `'.$title.'` has been restored.', 'flash_success');
        } else {
            $this->Session->setFlash('The file `'.$title.'` has NOT been restored.', 'flash_error');
        }

        $this->redirect(array('action' => 'index'));
    }
}