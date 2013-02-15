<?php

class MediaController extends AppController
{
	public $name = 'Media';
	public $uses = array(
		'Media'
	);
	private $permissions;

	public function beforeFilter()
	{
		parent::beforeFilter();

		if ($this->params->action == "admin_add" || $this->params->action == "admin_edit") {
			$this->loadModel('File');
			$this->paginate = array(
				'conditions' => array(
					'File.deleted_time' => '0000-00-00 00:00:00',
					'File.mimetype LIKE' => '%image%'
				),
				'limit' => 9
			);

			$images = $this->paginate('File');
			$image_path = WWW_ROOT;

			$this->set(compact('images', 'image_path'));
		}

		$this->permissions = $this->getPermissions();
	}

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

	public function admin_add()
	{
        if ($this->request->is('post')) {
        	$this->request->data['Media']['slug'] = $this->slug($this->request->data['Media']['title']);
        	$this->request->data['Media']['user_id'] = $this->Auth->user('id');
        	
            if ($this->Media->saveAll($this->request->data)) {
                $this->Session->setFlash('Your media library has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your media library.', 'flash_error');
            }
        }
	}

	public function admin_edit($id = null)
	{
      	$this->Media->id = $id;

	    if (!empty($this->request->data))
	    {
	    	$this->request->data['Media']['slug'] = $this->slug($this->request->data['Media']['title']);
	    	$this->request->data['Media']['user_id'] = $this->Auth->user('id');

	        if ($this->Media->saveAll($this->request->data)) {
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

	public function admin_delete($id = null, $title = null, $permanent = null)
	{
		if ($this->request->is('post')) {
	        throw new MethodNotAllowedException();
	    }

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

	    if (!empty($permanent)) {
	    	$delete = $this->Media->delete($id);
	    } else {
	    	$delete = $this->Media->saveField('deleted_time', $this->Media->dateTime());
	    }

	    if ($delete) {
	        $this->Session->setFlash('The media library `'.$title.'` has been deleted.', 'flash_success');
	    } else {
	    	$this->Session->setFlash('The media library `'.$title.'` has NOT been deleted.', 'flash_error');
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

        if ($this->Media->saveField('deleted_time', '0000-00-00 00:00:00')) {
            $this->Session->setFlash('The media library `'.$title.'` has been restored.', 'flash_success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The media library `'.$title.'` has NOT been restored.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }
    }

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
			'contain' => array(
				'File' => array(
					'conditions' => array(
						'File.deleted_time' => '0000-00-00 00:00:00'
					)
				)
			),
			'limit' => 9
		);

		$this->request->data = $this->paginate('Media');
	}

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

		if (empty($media)) {
			$this->Session->setFlash('No Library with the slug `' . $slug . '`');
			$this->redirect('/');
		}

		$this->set(compact('media'));
	}
}