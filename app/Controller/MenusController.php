<?php

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
	
        if ($this->params->action == 'admin_add' || $this->params->action == 'admin_edit')
        {
            $this->Security->validatePost = false;
            
            $pages = $this->Menu->User->Page->find('list');
            $categories = $this->Menu->User->Category->find('list');

            $this->set(compact('pages', 'categories'));
        }
    }

    /**
    * Returns a paginated index of Menus
    *
    * @return associative array of block data
    */
	public function admin_index()
	{
        $conditions = array();

	    if ($this->permissions['any'] == 0)
	    {
	    	$conditions['User.id'] = $this->Auth->user('id');
	    }

		if (!isset($this->params->named['trash'])) {
			$conditions['Menu.deleted_time'] = '0000-00-00 00:00:00';
        } else {
        	$conditions['Menu.deleted_time !='] = '0000-00-00 00:00:00';
        }

        $this->paginate = array(
            'order' => 'Menu.created DESC',
            'limit' => $this->pageLimit,
            'conditions' => array(
            	$conditions
            ),
            'contain' => array(
            	'User'
            )
        );
        
        $this->request->data = $this->paginate('Menu');

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
            ),
            'order' => ''
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
            $this->request->data['Menu']['user_id'] = $this->Auth->user('id');

            if ($this->Menu->save($this->request->data))
            {
                $this->Session->setFlash('Your menu has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your menu.', 'flash_error');
            }
        } 
    }

    /**
    * Before POST, sets request data to form
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param id ID of the database entry
    * @return associative array of menu data
    */
    public function admin_edit($id = null)
    {
        $this->Menu->id = $id;

        if (!empty($this->request->data))
        {           
            $this->request->data['Menu']['user_id'] = $this->Auth->user('id');
            
            if ($this->Menu->save($this->request->data))
            {
                $this->Session->setFlash('Your menu has been updated.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to update your menu.', 'flash_error');
            }
        }

        $this->request->data = $this->Menu->read();
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
        $this->Menu->id = $id;

        if (!empty($permanent))
        {
            $delete = $this->Menu->delete($id);
        } else {
            $delete = $this->Menu->saveField('deleted_time', $this->Menu->dateTime());
        }

        if ($delete)
        {
            $this->Session->setFlash('The menu `'.$title.'` has been deleted.', 'flash_success');
        } else {
            $this->Session->setFlash('The menu `'.$title.'` has NOT been deleted.', 'flash_error');
        }
        
        if (!empty($permanent))
        {
            $count = $this->Menu->find('count', array(
                'conditions' => array(
                    'Menu.deleted_time !=' => '0000-00-00 00:00:00'
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
        $this->Menu->id = $id;

        if ($this->Menu->saveField('deleted_time', '0000-00-00 00:00:00'))
        {
            $this->Session->setFlash('The menu `'.$title.'` has been restored.', 'flash_success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The menu `'.$title.'` has NOT been restored.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }
    }
}