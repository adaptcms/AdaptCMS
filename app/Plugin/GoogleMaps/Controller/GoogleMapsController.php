<?php
App::uses('AppController', 'Controller');

/**
 * Class MapsController
 *
 * @property GoogleMap $GoogleMap
 * @property Template $Template
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

		if ($this->request->action == 'admin_add' || $this->request->action == 'admin_edit') {
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
		$this->disable_parsing = true;

		$conditions = array();

		if ($this->permissions['any'] == 0)
			$conditions['User.id'] = $this->Auth->user('id');

		if (isset($this->request->named['trash']))
			$conditions['GoogleMap.only_deleted'] = true;

		$this->Paginator->settings = array(
			'conditions' => $conditions,
			'contain' => array(
				'User'
			)
		);

		$this->request->data = $this->Paginator->paginate('GoogleMap');

		$pages = $this->GoogleMap->User->Page->find('all');

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
		if (!empty($this->request->data)) {
			$this->GoogleMap->create();

			$this->request->data['GoogleMap']['user_id'] = $this->Auth->user('id');

			if ($this->GoogleMap->save($this->request->data)) {
				$this->Session->setFlash('Your Map has been added.', 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to add your Map.', 'error');
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
	public function admin_edit($id)
	{
		$this->GoogleMap->id = $id;

		if (!empty($this->request->data)) {
			$this->request->data['GoogleMap']['user_id'] = $this->Auth->user('id');

			if ($this->GoogleMap->save($this->request->data)) {
				$this->Session->setFlash('Your Map has been updated.', 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to update your Map.', 'error');
			}
		}

		$this->request->data = $this->GoogleMap->read();

		$path = $this->GoogleMap->_getPath($this->request->data['GoogleMap']['slug']);
		if (is_writable($path)) {
			$writable = 1;
		} else {
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
		$this->GoogleMap->id = $id;

		$data = $this->GoogleMap->findById($id);
		$this->hasAccessToItem($data);

		$permanent = $this->GoogleMap->remove($data);

		$this->Session->setFlash('The map `' . $title . '` has been deleted.', 'success');

		if ($permanent) {
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
		$this->GoogleMap->id = $id;

		if ($this->GoogleMap->restore()) {
			$this->Session->setFlash('The map `' . $title . '` has been restored.', 'success');
			$this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash('The map `' . $title . '` has NOT been restored.', 'error');
			$this->redirect(array('action' => 'index'));
		}
	}
}