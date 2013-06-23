<?php
App::uses('AppController', 'Controller');

/**
 * Class MediaController
 */
class MediaController extends AppController
{
    /**
    * Name of the Controller, 'Media'
    */
	public $name = 'Media';

	/**
	* Cake is expecting Medias controller using model media, both controller and model are named 'Media'
	*/
	public $uses = array(
		'Media'
	);

    /**
    * In this beforeFilter we will get the permissions to be used in the view files
    * If this is an admin add or edit action, an array of images and the image path is set to the view
    */
	private $permissions;

	public function beforeFilter()
	{
		parent::beforeFilter();

		if ($this->params->action == "admin_add" || $this->params->action == "admin_edit")
		{
			$this->loadModel('File');
			$this->paginate = array(
				'conditions' => array(
					'File.deleted_time' => '0000-00-00 00:00:00',
					'File.mimetype LIKE' => '%image%'
				),
				'order' => 'File.created DESC',
				'limit' => 9
			);

			$images = $this->paginate('File');
			$image_path = WWW_ROOT;

			$this->set(compact('images', 'image_path'));
		}

		$this->permissions = $this->getPermissions();
	}

    /**
    * Returns a paginated index of Media Libraries
    *
    * @return associative array of media data
    */
	public function admin_index()
	{
		$conditions = array();

		if (!isset($this->params->named['trash'])) {
			$conditions['Media.deleted_time'] = '0000-00-00 00:00:00';
		} else {
			$conditions['Media.deleted_time !='] = '0000-00-00 00:00:00';
        }

	    if ($this->permissions['any'] == 0)
	    {
	    	$conditions['User.id'] = $this->Auth->user('id');
	    }

		$this->paginate = array(
            'order' => 'Media.created DESC',
            'limit' => $this->pageLimit,
            'conditions' => $conditions,
            'contain' => array(
            	'File',
            	'User'
            )
        );
        
		$this->request->data = $this->paginate('Media');
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
        if (!empty($this->request->data))
        {
        	$this->request->data['Media']['user_id'] = $this->Auth->user('id');
        	
            if ($this->Media->saveAll($this->request->data))
            {
                $this->Session->setFlash('Your media library has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your media library.', 'flash_error');
            }
        }
	}

    /**
    * Before POST, sets request data to form
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param id ID of the database entry, redirect to index if no permissions
    * @return associative array of media data
    */
	public function admin_edit($id = null)
	{
      	$this->Media->id = $id;

	    if (!empty($this->request->data))
	    {
	    	$this->request->data['Media']['user_id'] = $this->Auth->user('id');

	        if ($this->Media->saveAll($this->request->data))
	        {
	            $this->Session->setFlash('Your media library has been updated.', 'flash_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your media library.', 'flash_error');
	        }
	    }

        $this->request->data = $this->Media->find('first', array(
        	'conditions' => array(
        		'Media.id' => $id
        	),
        	'contain' => array(
        		'File',
        		'User'
        	)
        ));

        if ($this->request->data['User']['id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect(array('action' => 'index'));	        	
        }
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
	    $this->Media->id = $id;

        $data = $this->Media->find('first', array(
        	'conditions' => array(
        		'Media.id' => $id
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
	    	$delete = $this->Media->delete($id);
	    } else {
	    	$delete = $this->Media->saveField('deleted_time', $this->Media->dateTime());
	    }

	    if ($delete)
	    {
	        $this->Session->setFlash('The media library `'.$title.'` has been deleted.', 'flash_success');
	    } else {
	    	$this->Session->setFlash('The media library `'.$title.'` has NOT been deleted.', 'flash_error');
	    }

	    if (!empty($permanent))
	    {
	    	$count = $this->Media->find('count', array(
	    		'conditions' => array(
	    			'Media.deleted_time !=' => '0000-00-00 00:00:00'
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
        $this->Media->id = $id;

        $data = $this->Media->find('first', array(
        	'conditions' => array(
        		'Media.id' => $id
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

        if ($this->Media->saveField('deleted_time', '0000-00-00 00:00:00'))
        {
            $this->Session->setFlash('The media library `'.$title.'` has been restored.', 'flash_success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The media library `'.$title.'` has NOT been restored.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }
    }

    /**
    * Index list will return list of media albums and related files
    *
    * TODO: Brings back ALL related files and does a count. If limit of 1 is set in the contain, it brings back the first overall
    * file. Need to do two things - 1. Do a count in the query on the amount of files (possibly separate?) and then bring back
    * only the newest file per each album.
    *
    * @return associative array of data
    */
	public function index()
	{
		$conditions = array();

		$conditions['Media.deleted_time'] = '0000-00-00 00:00:00';

	    if ($this->permissions['any'] == 0)
	    {
	    	$conditions['User.id'] = $this->Auth->user('id');
	    }

		$this->paginate = array(
			'conditions' => $conditions,
			'limit' => 9
		);

		$this->request->data = $this->Media->getLastFileAndCount($this->paginate('Media'));

		$this->set('media', $this->request->data);
	}

	/**
	* Function finds the media album by slug and gets a paginated list of files related to it.
	* Coding wise is a bit tricky due to using a find with a 'HABTM' relationship, but joins do the trick.
	*
	* @param slug of album
	* @return associative array of data
	*/
	public function view($slug = null)
	{
		$media_conditions = array();
		$file_conditions = array();

		$media_conditions['Media.slug'] = $slug;

	    if ($this->permissions['any'] == 0)
	    {
	    	$media_conditions['User.id'] = $this->Auth->user('id');
	    }

	    $file_conditions = 'File.id = MediaFile.file_id';

	    if ($this->permissions['related']['files']['view']['any'] == 0)
	    {
	    	$file_conditions['User.id'] = $this->Auth->user('id');
	    }

		$joins = array(
			array(
		        'table' => 'media_files',
		        'alias' => 'MediaFile',
		        'conditions' => $file_conditions
			),
			array(
		        'table' => 'media',
		        'alias' => 'Media',
		        'conditions' => array(
		            'Media.id = MediaFile.media_id',
		            "Media.slug = '".$slug."'"
		        )
			)
		);

		$this->paginate = array(
			'joins' => $joins,
			'limit' => 9
		);

		$this->request->data = $this->paginate('File');

		$media = $this->Media->find('first', array(
			'conditions' => $media_conditions
		));

		if (empty($media))
		{
			$this->Session->setFlash('No Library with the slug `' . $slug . '`');
			$this->redirect('/');
		}

		$this->set(compact('media'));
		$this->set('files', $this->request->data);
	}
}