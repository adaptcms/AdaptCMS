<?php
App::uses('LinksAppController', 'Links.Controller');
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

		if ($this->request->action == "admin_add" || $this->request->action == "admin_edit") {
			$this->loadModel('File');
			$this->Paginator->settings = array(
				'conditions' => array(
					'File.mimetype LIKE' => '%image%'
				),
				'limit' => 9
			);

			$images = $this->Paginator->paginate('File');
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

		if (isset($this->request->named['trash']))
	        $conditions['Link.only_deleted'] = true;

	    if ($this->permissions['any'] == 0)
	    	$conditions['User.id'] = $this->Auth->user('id');

        $this->Paginator->settings = array(
            'limit' => $this->pageLimit,
            'conditions' => $conditions,
            'contain' => array(
            	'User'
            )
        );

        $this->request->data = $this->Paginator->paginate('Link');
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
	public function admin_edit($id)
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
		$this->hasAccessToItem($this->request->data);
	}

    /**
     * If item has no delete time, then initial deletion is to the trash area (making it in-active on site, if applicable)
     *
     * But if it has a deletion time, meaning it is in the trash, deleting it the second time is permanent.
     *
     * @param integer $id of the database entry, redirect to index if no permissions
     * @param string $title of this entry, used for flash message
     * @return void
     */
	public function admin_delete($id, $title = null)
	{
	    $this->Link->id = $id;

		$data = $this->Link->findById($id);
		$this->hasAccessToItem($data);

		$permanent = $this->Link->remove($data);

		$this->Session->setFlash('The link `'.$title.'` has been deleted.', 'flash_success');

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
    * @param integer $id of database entry, redirect if no permissions
    * @param string $title of this entry, used for flash message
    * @return void
    */
    public function admin_restore($id, $title = null)
    {
        $this->Link->id = $id;

	    $data = $this->Link->findById($id);
	    $this->hasAccessToItem($data);

        if ($this->Link->restore())
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

    /**
     * Apply
     *
     * @return void
     */
    public function apply()
    {
        if (!$this->Auth->user('id') && Configure::read('Links.captcha_for_guests_submit_page'))
        {
            $captcha = true;
            $this->set(compact('captcha'));
        }

        if (!empty($this->request->data))
        {
            $this->request->data['Link']['user_id'] = (!$this->Auth->user('id') ? 0 : $this->Auth->user('id'));
            $this->request->data['Link']['active'] = 0;

            if (!empty($captcha))
            {
                include_once(APP . 'webroot/libraries/captcha/securimage.php');
                $securimage = new Securimage();

                if (!empty($securimage) &&
                    !$securimage->check($this->request->data['captcha']))
                {
                    $this->Session->setFlash('Incorrect captcha entred.', 'flash_error');
                    $error = true;
                }
            }

            if (empty($error))
            {
                if ($this->Link->save($this->request->data))
                {
                    $this->Session->setFlash('The Link has been submitted. ' . Configure::read('Links.text_on_success_submit'), 'flash_success');
                    $this->redirect('/');
                } else {
                    $this->Session->setFlash('The Link has NOT been submitted. Check errors below.', 'flash_error');
                }
            }
        }
    }
}