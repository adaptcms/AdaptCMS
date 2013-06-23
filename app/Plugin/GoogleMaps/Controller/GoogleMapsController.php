<?php
App::uses('AppController', 'Controller');

/**
 * Class MapsController
 * @property GoogleMap $GoogleMap
 * @property Template $Template
 * @property params $params
 * @property paginate $paginate
 * @property redirect $redirect
 * @property pageLimit $pageLimit
 */
class GoogleMapsController extends AppController
{
    /**
     * Name of the Controller, 'Maps'
     */
    public $name = 'GoogleMaps';

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

            $this->set('defaults', $this->GoogleMap->map_defaults);
            $this->set('zoom', $this->GoogleMap->getZoomNumbers());
            $this->set('colors', $this->GoogleMap->getMarkerColors());
            $this->set('sizes', $this->GoogleMap->getMarkerSizes());
        }

        $this->set('map_types', $this->GoogleMap->map_types);
    }

    /**
     * Returns a paginated index of Maps
     *
     * @return array of block data
     */
    public function admin_index()
    {
        $conditions = array();

        if ($this->permissions['any'] == 0)
        {
            $conditions['User.id'] = $this->Auth->user('id');
        }

        if (!isset($this->params->named['trash'])) {
            $conditions['GoogleMap.deleted_time'] = '0000-00-00 00:00:00';
        } else {
            $conditions['GoogleMap.deleted_time !='] = '0000-00-00 00:00:00';
        }

        $this->paginate = array(
            'order' => 'GoogleMap.created DESC',
            'limit' => $this->pageLimit,
            'conditions' => array(
                $conditions
            ),
            'contain' => array(
                'User'
            )
        );

        $this->request->data = $this->paginate('GoogleMap');

        $this->loadModel('Template');
        $this->loadModel('Page');

        $pages = $this->Page->find('all');

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

        $this->set(compact('templates', 'pages'));
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
            $this->request->data['GoogleMap']['user_id'] = $this->Auth->user('id');

            if ($this->GoogleMap->save($this->request->data))
            {
                $this->Session->setFlash('Your Map has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your Map.', 'flash_error');
            }
        }
    }

    /**
     * Before POST, sets request data to form
     *
     * After POST, flash error or flash success and redirect to index
     *
     * @param int - ID of the database entry
     * @return array of GoogleMap data
     */
    public function admin_edit($id = null)
    {
        $this->GoogleMap->id = $id;

        if (!empty($this->request->data))
        {
            $this->request->data['GoogleMap']['user_id'] = $this->Auth->user('id');

            if ($this->GoogleMap->save($this->request->data))
            {
                $this->Session->setFlash('Your Map has been updated.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to update your Map.', 'flash_error');
            }
        }

        $this->request->data = $this->GoogleMap->read();

        $path = $this->GoogleMap->_getPath($this->request->data['GoogleMap']['slug']);
        if (is_writable($path))
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
     * @param boolean $permanent
     * @internal param \If $permanent not NULL, this means the item is in the trash so deletion will now be permanent
     * @return redirect
     */
    public function admin_delete($id = null, $title = null, $permanent = null)
    {
        $this->GoogleMap->id = $id;

        if (!empty($permanent))
        {
            $delete = $this->GoogleMap->delete($id);
        } else {
            $delete = $this->GoogleMap->saveField('deleted_time', $this->GoogleMap->dateTime());
        }

        if ($delete)
        {
            $this->Session->setFlash('The Map `'.$title.'` has been deleted.', 'flash_success');
        } else {
            $this->Session->setFlash('The Map `'.$title.'` has NOT been deleted.', 'flash_error');
        }

        if (!empty($permanent))
        {
            $count = $this->GoogleMap->find('count', array(
                'conditions' => array(
                    'GoogleMap.deleted_time !=' => '0000-00-00 00:00:00'
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
     * @param int - ID of database entry, redirect if no permissions
     * @param string - Title of this entry, used for flash message
     * @return redirect
     */
    public function admin_restore($id = null, $title = null)
    {
        $this->GoogleMap->id = $id;

        if ($this->GoogleMap->saveField('deleted_time', '0000-00-00 00:00:00'))
        {
            $this->Session->setFlash('The Map `'.$title.'` has been restored.', 'flash_success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The Map `'.$title.'` has NOT been restored.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }
    }
}