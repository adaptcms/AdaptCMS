<?php

class MediaController extends AppController
{
	public $name = 'Media';
	public $uses = array(
		'Media'
	);

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
	}

	public function admin_index()
	{
		if (!isset($this->params->named['trash'])) {
			$this->paginate = array(
	            'order' => 'Media.created DESC',
	            'limit' => $this->pageLimit,
	            'conditions' => array(
	            	'Media.deleted_time' => '0000-00-00 00:00:00'
	            ),
	            'contain' => array(
	            	'File'
	            )
	        );
		} else {
			$this->paginate = array(
	            'order' => 'Media.created DESC',
	            'limit' => $this->pageLimit,
	            'conditions' => array(
	            	'Media.deleted_time !=' => '0000-00-00 00:00:00'
	            )
	        );
        }
        
		$this->request->data = $this->paginate('Media');
	}

	public function admin_add()
	{
        if ($this->request->is('post')) {
        		$this->request->data['Media']['slug'] = $this->slug($this->request->data['Media']['title']);
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

	    if ($this->request->is('get')) {
	        $this->request->data = $this->Media->find('first', array(
	        	'conditions' => array(
	        		'Media.id' => $id
	        	),
	        	'contain' => array(
	        		'File'
	        	)
	        ));
	    } else {
	    	$this->request->data['Media']['slug'] = $this->slug($this->request->data['Media']['title']);

	        if ($this->Media->saveAll($this->request->data)) {
	            $this->Session->setFlash('Your media library has been updated.', 'flash_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your media library.', 'flash_error');
	        }
	    }
	}

	public function admin_delete($id = null, $title = null, $permanent = null)
	{
		if ($this->request->is('post')) {
	        throw new MethodNotAllowedException();
	    }

	    $this->Media->id = $id;

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

	public function index()
	{
		$this->paginate = array(
			'conditions' => array(
				'Media.deleted_time' => '0000-00-00 00:00:00'
			),
			'contain' => array(
				'File' => array(
					'conditions' => array(
						'File.deleted_time' => '0000-00-00 00:00:00'
					)
				)
			)
		);

		$this->request->data = $this->paginate('Media');
	}

	public function view($slug = null)
	{
		$joins = array(
			array(
		        'table' => 'media_files',
		        'alias' => 'MediaFile',
		        'conditions' => array(
		            'File.id = MediaFile.file_id',
		        )
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
			'joins' => $joins
		);

		$this->request->data = $this->paginate('File');

		$media = $this->Media->findBySlug($slug);

		if (empty($media)) {
			$this->Session->setFlash('No Library with the slug `' . $slug . '`');
			$this->redirect('/');
		}

		$this->set(compact('media'));
	}
}