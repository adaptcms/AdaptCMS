<?php
App::uses('AppController', 'Controller');

/**
 * Class MenusController
 * @property Menu $Menu
 * @property Template $Template
 */
class MenusController extends AppController
{
    /**
    * Name of the Controller, 'Menus'
    */
	public $name = 'Menus';

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
	
        if ($this->request->action == 'admin_add' || $this->request->action == 'admin_edit')
        {
            $this->Security->validatePost = false;
            
            $pages = $this->Menu->User->Page->find('list');
            $categories = $this->Menu->User->Category->find('list');

            $separator_types = $this->Menu->getSeparatorTypes();
            $header_types = $this->Menu->getHeaderTypes();

            $this->set(compact('pages', 'categories', 'header_types', 'separator_types'));
        }
    }

    /**
    * Returns a paginated index of Menus
    *
    * @return array of block data
    */
	public function admin_index()
	{
        $conditions = array();

	    if ($this->permissions['any'] == 0)
	    	$conditions['User.id'] = $this->Auth->user('id');

		if (isset($this->request->named['trash']))
			$conditions['Menu.only_deleted'] = true;

        $this->Paginator->settings = array(
            'conditions' => $conditions,
            'contain' => array(
            	'User'
            )
        );
        
        $this->request->data = $this->Paginator->paginate('Menu');

        $this->loadModel('Template');

        $templates = $this->Template->find('all', array(
            'conditions' => array(
                'Template.location LIKE' => '%Layouts%',
                'NOT' => array(
                    array('Template.location LIKE' => '%Layouts/rss%'),
                    array('Template.location LIKE' => '%Layouts/js%'),
                    array('Template.location LIKE' => '%Layouts/xml%'),
                    array('Template.location LIKE' => '%Layouts/Emails%')
                )
            )
        ));

        $this->set(compact('templates'));
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
	        $this->Menu->create();

            $this->request->data['Menu']['user_id'] = $this->Auth->user('id');

            if ($this->Menu->save($this->request->data))
            {
                $this->Session->setFlash('Your menu has been added.', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your menu.', 'error');
            }
        } 
    }

    /**
    * Before POST, sets request data to form
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param int - ID of the database entry
    * @return array of menu data
    */
    public function admin_edit($id)
    {
        $this->Menu->id = $id;

        if (!empty($this->request->data))
        {           
            $this->request->data['Menu']['user_id'] = $this->Auth->user('id');
            
            if ($this->Menu->save($this->request->data))
            {
                $this->Session->setFlash('Your menu has been updated.', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to update your menu.', 'error');
            }
        }

        $this->request->data = $this->Menu->read();
	    $this->hasAccessToItem($this->request->data);

        $path = $this->Menu->_getPath($this->request->data['Menu']['slug']);
        if (!file_exists($path) || is_writable($path))
        {
            $writable = 1;
        }
        else
        {
            $writable = $path;
        }

        $this->set(compact('writable'));
    }

    /**
     * If item has no delete time, then initial deletion is to the trash area (making it in-active on site, if applicable)
     *
     * But if it has a deletion time, meaning it is in the trash, deleting it the second time is permanent.
     *
     * @param int - ID of the database entry, redirect to index if no permissions
     * @param string - Title of this entry, used for flash message
     * @return void
     */
    public function admin_delete($id, $title = null)
    {
        $this->Menu->id = $id;

	    $data = $this->Menu->findById($id);
	    $this->hasAccessToItem($data);

	    $permanent = $this->Menu->remove($data);

	    $this->Session->setFlash('The menu `'.$title.'` has been deleted.', 'success');

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
    * @param int - ID of database entry, redirect if no permissions
    * @param string - Title of this entry, used for flash message
    * @return void
    */
    public function admin_restore($id, $title = null)
    {
        $this->Menu->id = $id;

        if ($this->Menu->restore())
        {
            $this->Session->setFlash('The menu `'.$title.'` has been restored.', 'success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The menu `'.$title.'` has NOT been restored.', 'error');
            $this->redirect(array('action' => 'index'));
        }
    }
}