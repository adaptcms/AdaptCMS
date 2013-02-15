<?php

class LinksController extends LinksAppController
{
	public $name = 'Links';
	private $permissions;

	public function beforeFilter()
	{
		$this->allowedActions = array('track');

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

	        if (!$this->request->is('get')) {
	        	if (!empty($this->request->data['File'])) {
	        		foreach($this->request->data['File'] as $file) {
	        			$this->request->data['Link']['file_id'] = $file;
	        		}
	        	}

	        	if (empty($this->request->data['Link']['link_title'])) {
	        		$this->request->data['Link']['link_title'] = $this->request->data['Link']['title'];
	        	}
	        }
		}

		$this->permissions = $this->getPermissions();
	}

	public function admin_index()
	{
		$conditions = array();

		if (!isset($this->params->named['trash'])) {
	        $conditions['Link.deleted_time'] = '0000-00-00 00:00:00';
	    } else {
	        $conditions['Link.deleted_time !='] = '0000-00-00 00:00:00';
        }

	    if ($this->permissions['any'] == 0)
	    {
	    	$conditions['User.id'] = $this->Auth->user('id');
	    }

        $this->paginate = array(
            'order' => 'Link.created DESC',
            'limit' => $this->pageLimit,
            'conditions' => $conditions,
            'contain' => array(
            	'User'
            )
        );

        $this->request->data = $this->paginate('Link');
	}

	public function admin_add()
	{
        if ($this->request->is('post')) {
            if ($this->Link->save($this->request->data)) {
                $this->Session->setFlash('Your link has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your link.', 'flash_error');
            }
        } 
	}

	public function admin_edit($id = null)
	{

      	$this->Link->id = $id;

	    if (!empty($this->request->data))
	    {
	    	$this->request->data['Link']['user_id'] = $this->Auth->user('id');

	        if ($this->Link->save($this->request->data)) {
	            $this->Session->setFlash('Your link has been updated.', 'flash_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your link.', 'flash_error');
	        }
	    }

        $this->request->data = $this->Link->find('first', array(
        	'conditions' => array(
        		'Link.id' => $id
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

	    $this->Link->id = $id;

        $data = $this->Link->find('first', array(
        	'conditions' => array(
        		'Link.id' => $id
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
            $delete = $this->Link->delete($id);
        } else {
            $delete = $this->Link->saveField('deleted_time', $this->Link->dateTime());
        }

	    if ($delete) {
	        $this->Session->setFlash('The link `'.$title.'` has been deleted.', 'flash_success');
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash('The link `'.$title.'` has NOT been deleted.', 'flash_error');
	        $this->redirect(array('action' => 'index'));
	    }
	}

    public function admin_restore($id = null, $title = null)
    {
        if ($this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $this->Link->id = $id;

        $data = $this->Link->find('first', array(
        	'conditions' => array(
        		'Link.id' => $id
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

        if ($this->Link->saveField('deleted_time', '0000-00-00 00:00:00')) {
            $this->Session->setFlash('The link `'.$title.'` has been restored.', 'flash_success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The link `'.$title.'` has NOT been restored.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }
    }

    public function track()
    {
    	$this->layout = 'ajax';
    	$this->autoRender = null;

    	if (!empty($this->request->data['Link']['id'])) {
    		$id = $this->request->data['Link']['id'];
    	}

    	if (!empty($id)) {
    		$find = $this->Link->findById($id);
    		$views = $find['Link']['views'] + 1;

    		$this->Link->id = $id;
    		$this->Link->saveField('views', $views);
    	}

    	return $views;
    }
}