<?php
App::uses('AppController', 'Controller');
/**
 * Class LinksController
 * @property Link $Link
 */
class LinksController extends LinksAppController
{
    /**
    * Name of the Controller, 'Links'
    */
	public $name = 'Links';

    /**
    * array of permissions for this page
    */
	private $permissions;

    /**
    * In this beforeFilter we will get the permissions to be used in the view files
    * We add 'track' as an allowed function, as anyone viewing the link should be able to click on it.
    *
    * If the current action is add or edit, we pass a list of the latest 9 files for the media modal.
    */
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
		}

		$this->permissions = $this->getPermissions();
	}

    /**
    * Returns a paginated index of Links
    *
    * @return array of links data
    */
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
            $this->Link->create();

            $this->request->data['Link']['user_id'] = $this->Auth->user('id');

            if ($this->Link->save($this->request->data))
            {
                $this->Session->setFlash('Your link has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your link.', 'flash_error');
            }
        } 
	}

    /**
    * Before POST, sets request data to form
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param integer $id of the database entry, redirect to index if no permissions
    * @return array of link data
    */
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

    /**
     * If item has no delete time, then initial deletion is to the trash area (making it in-active on site, if applicable)
     *
     * But if it has a deletion time, meaning it is in the trash, deleting it the second time is permanent.
     *
     * @param integer $id of the database entry, redirect to index if no permissions
     * @param string $title of this entry, used for flash message
     * @param string $permanent
     * @internal param \If $permanent not NULL, this means the item is in the trash so deletion will now be permanent
     * @return redirect
     */
	public function admin_delete($id = null, $title = null, $permanent = null)
	{
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

        if (!empty($permanent))
        {
            $delete = $this->Link->delete($id);
        } else {
            $delete = $this->Link->saveField('deleted_time', $this->Link->dateTime());
        }

	    if ($delete)
        {
	        $this->Session->setFlash('The link `'.$title.'` has been deleted.', 'flash_success');
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash('The link `'.$title.'` has NOT been deleted.', 'flash_error');
	        $this->redirect(array('action' => 'index'));
	    }
	}

    /**
    * Restoring an item will take an item in the trash and reset the delete time
    *
    * This makes it live wherever applicable
    *
    * @param integer $id of database entry, redirect if no permissions
    * @param string $title of this entry, used for flash message
    * @return redirect
    */
    public function admin_restore($id = null, $title = null)
    {
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

        if ($this->Link->saveField('deleted_time', '0000-00-00 00:00:00'))
        {
            $this->Session->setFlash('The link `'.$title.'` has been restored.', 'flash_success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The link `'.$title.'` has NOT been restored.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }
    }

    /**
    * A simple ajax function that when a link is clicked, a request is made.
    * If the ID is valid, a find is made to get the amount of views + 1 and saved.
    *
    * @return integer of link views or false with invalid ID
    */
    public function track()
    {
    	$this->layout = 'ajax';
    	$this->autoRender = null;

    	if (!empty($this->request->data['Link']['id']))
        {
    		$id = $this->request->data['Link']['id'];
    	}

    	if (!empty($id))
        {
    		$find = $this->Link->findById($id);
    		$views = $find['Link']['views'] + 1;

    		$this->Link->id = $id;
    		$this->Link->saveField('views', $views);

            return $views;
    	}

    	return false;
    }
}