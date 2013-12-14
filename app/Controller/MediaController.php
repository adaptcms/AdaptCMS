<?php
App::uses('AppController', 'Controller');

/**
 * Class MediaController
 *
 * @property Media $Media
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

    public $cacheAction = '1 day';

    /**
    * In this beforeFilter we will get the permissions to be used in the view files
    * If this is an admin add or edit action, an array of images and the image path is set to the view
    */
	private $permissions;

	public function beforeFilter()
	{
		parent::beforeFilter();

		if ($this->request->action == "admin_add" || $this->request->action == "admin_edit")
		{
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
    * Returns a paginated index of Media Libraries
    *
    * @return array Array of media data
    */
	public function admin_index()
	{
		$conditions = array();

		if (isset($this->request->named['trash']))
			$conditions['Media.only_deleted'] = true;

	    if ($this->permissions['any'] == 0)
	    	$conditions['User.id'] = $this->Auth->user('id');

		$this->Paginator->settings = array(
            'conditions' => $conditions,
            'contain' => array(
            	'File',
            	'User'
            )
        );
        
		$this->request->data = $this->Media->getFileCount($this->Paginator->paginate('Media'));
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
	        $this->Media->create();

        	$this->request->data['Media']['user_id'] = $this->Auth->user('id');
        	
            if ($this->Media->saveAll($this->request->data))
            {
                $this->Session->setFlash('Your media library has been added.', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your media library.', 'error');
            }
        }
	}

    /**
    * Before POST, sets request data to form
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param integer $id id of the database entry, redirect to index if no permissions
    * @return array Array of media data
    */
	public function admin_edit($id)
	{
      	$this->Media->id = $id;

	    if (!empty($this->request->data))
	    {
	    	$this->request->data['Media']['user_id'] = $this->Auth->user('id');

	        if ($this->Media->saveAll($this->request->data))
	        {
	            $this->Session->setFlash('Your media library has been updated.', 'success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your media library.', 'error');
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
		$this->hasAccessToItem($this->request->data);
	}

    /**
    * If item has no delete time, then initial deletion is to the trash area (making it in-active on site, if applicable)
    *
    * But if it has a deletion time, meaning it is in the trash, deleting it the second time is permanent.
    *
    * @param integer $id id of the database entry, redirect to index if no permissions
    * @param string $title Title of this entry, used for flash message
    * @return void
    */
	public function admin_delete($id, $title = null)
	{
	    $this->Media->id = $id;

		$data = $this->Media->findById($id);
		$this->hasAccessToItem($data);

		$permanent = $this->Media->remove($data);

		$this->Session->setFlash('The media library `'.$title.'` has been deleted.', 'success');

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
    * @param integer $id ID of database entry, redirect if no permissions
    * @param string $title Title of this entry, used for flash message
    * @return void
    */
    public function admin_restore($id, $title = null)
    {
        $this->Media->id = $id;

	    $data = $this->Media->findById($id);
	    $this->hasAccessToItem($data);

        if ($this->Media->restore())
        {
            $this->Session->setFlash('The media library `'.$title.'` has been restored.', 'success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The media library `'.$title.'` has NOT been restored.', 'error');
            $this->redirect(array('action' => 'index'));
        }
    }

    /**
    * Index list will return list of media albums and related files
    *
    * @return array Array of data
    */
	public function index()
	{
		$conditions = array();

	    if ($this->permissions['any'] == 0)
	    	$conditions['User.id'] = $this->Auth->user('id');

		$this->Paginator->settings = array(
			'conditions' => $conditions,
			'limit' => 9
		);

		$this->request->data = $this->Media->getLastFileAndCount($this->Paginator->paginate('Media'));

		$this->set('media', $this->request->data);
	}

	/**
	* Function finds the media album by slug and gets a paginated list of files related to it.
	* Coding wise is a bit tricky due to using a find with a 'HABTM' relationship, but joins do the trick.
	*
	* @param null $slug Slug of album
	* @return array Array of data
	*/
	public function view($slug = null)
	{
		$media_conditions = array();

		$media_conditions['Media.slug'] = $slug;

	    if ($this->permissions['any'] == 0)
	    	$media_conditions['User.id'] = $this->Auth->user('id');

		$media = $this->Media->find('first', array(
			'conditions' => $media_conditions
		));

		if (empty($media))
		{
			$this->Session->setFlash('No Library with the slug `' . $slug . '`');
			$this->redirect('/');
		}

	    $file_conditions = 'File.id = MediaFile.file_id';

	    if ($this->permissions['related']['files']['view']['any'] == 0)
	    	$file_conditions['User.id'] = $this->Auth->user('id');

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

		$this->Paginator->settings = array(
			'joins' => $joins,
			'limit' => 9
		);

		$this->request->data = $this->Paginator->paginate('File');

		$this->set(compact('media'));
		$this->set('files', $this->request->data);
	}
}