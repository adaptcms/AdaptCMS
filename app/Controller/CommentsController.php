<?php
App::uses('AppController', 'Controller');

/**
 * Class CommentsController
 *
 * @property Comment $Comment
 */
class CommentsController extends AppController
{
    /**
    * Name of the Controller, 'Comments'
    */
	public $name = 'Comments';

	private $permissions;

    public function beforeFilter()
    {
        $this->Security->unlockedActions = array('ajax_post');

        parent::beforeFilter();

	    $this->permissions = $this->getPermissions();
    }

	/**
	 * Admin Index
	 * Returns a paginated index of Categories
	 *
	 * @return void
	 */
	public function admin_index()
	{
		$conditions = array();

		if ($this->permissions['any'] == 0)
			$conditions['User.id'] = $this->Auth->user('id');

		if (isset($this->request->named['trash']))
			$conditions['Comment.only_deleted'] = true;

		$this->Paginator->settings = array(
			'conditions' => $conditions,
			'contain' => array(
				'User',
				'Article'
			)
		);

		$this->request->data = $this->Paginator->paginate('Comment');
	}

    /**
    * Flash error or flash success and redirect back to article
    *
    * @param integer $id of the database entry
    * @return void
    */
    public function admin_edit($id)
    {
        if (!empty($this->request->data))
        {
	        if (!empty($this->request->data['Comment']['single']))
	        {
		        if ($this->Comment->save($this->request->data))
		        {
			        if (!empty($this->request->data['ModuleValue']))
			        {
				        $this->loadModel('ModuleValue');
				        $this->ModuleValue->saveMany($this->request->data['ModuleValue']);
			        }

			        $this->Session->setFlash('The comment has been updated.', 'success');
			        $this->redirect(array('action' => 'index'));
		        } else {
			        $this->Session->setFlash('Unable to update the comment.', 'error');
		        }
	        }
	        else
	        {
	            unset($this->request->data['Comment']);

	            if ($this->Comment->saveAll($this->request->data)) {
	                $this->Session->setFlash('Comments have been updated.', 'success');
	                $this->redirect(
	                    array(
	                        'controller' => 'articles',
	                        'action' => 'edit',
	                        $id,
	                        'comments'
	                    )
	                );
	            } else {
	                $this->Session->setFlash('Unable to update comments.', 'error');
	            }
	        }
        }

	    $this->request->data = $this->Comment->find('first', array(
		    'conditions' => array(
			    'Comment.id' => $id
		    ),
		    'contain' => array(
			    'User',
			    'Article'
		    )
	    ));

	    $fields = $this->Comment->Article->Category->Field->getFields('Comment', $this->request->data['Comment']['id']);
	    $field_data = $this->Comment->Article->Category->Field->getData('Comment', $this->request->data['User']['id'], $fields);

	    $this->set(compact('fields', 'field_data'));
    }

	/**
	 * If item has no delete time, then initial deletion is to the trash area (making it in-active on site, if applicable)
	 *
	 * But if it has a deletion time, meaning it is in the trash, deleting it the second time is permanent.
	 *
	 * @param integer $id id of the database entry, redirect to index if no permissions
	 * @return void
	 */
	public function admin_delete($id)
	{
		$this->Comment->id = $id;

		$data = $this->Comment->findById($id);
		$this->hasAccessToItem($data);

		$permanent = $this->Comment->remove($data);

		$this->Session->setFlash('The comment has been deleted.', 'success');

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
	 * @return void
	 */
	public function admin_restore($id = null)
	{
		$this->Comment->id = $id;

		$data = $this->Comment->findById($id);
		$this->hasAccessToItem($data);

		if ($this->Comment->restore())
		{
			$this->Session->setFlash('The comment has been restored.', 'success');
			$this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash('The comment has NOT been restored.', 'error');
			$this->redirect(array('action' => 'index'));
		}
	}
	
    /**
    * AJAX Method to post comment.
    *
    * @return array of message, status
    */
	public function ajax_post()
	{
    	$this->layout = 'ajax';
    	$this->autoRender = false;

    	$this->request->data['Comment']['author_ip'] = $_SERVER['REMOTE_ADDR'];
    	$this->request->data['Comment']['active'] = 1;
    	$this->request->data['Comment']['created'] = date('Y-m-d H:i:s');

        if ($this->Auth->user('id'))
        {
            $this->request->data['Comment']['user_id'] = $this->Auth->user('id');
        }

    	$this->loadModel('SettingValue');
    	$flood = $this->SettingValue->findByTitle('Comment Post Flood Limit');
        $captcha = $this->SettingValue->findByTitle('Comment Post Captcha Non-Logged In');
        $html_tags = $this->SettingValue->findByTitle('Comment Allowed HTML');

        if (!empty($html_tags['SettingValue']['data']))
        {
            $this->request->data['Comment']['comment_text'] = strip_tags(
                $this->request->data['Comment']['comment_text'],
                $html_tags['SettingValue']['data']
            );
        } else {
            $this->request->data['Comment']['comment_text'] = strip_tags(
                $this->request->data['Comment']['comment_text']
            );
        }

        if (!empty($captcha['SettingValue']['data']) && $captcha['SettingValue']['data'] == 'Yes' && 
            !$this->Auth->user('id') && 
            !$this->checkCaptcha($this->request->data['captcha']))
        {
            $message = 'Invalid Captcha Answer. Please try again.';
        }

    	if (!empty($flood['SettingValue']['data']) && $flood['SettingValue']['data'] != 0)
        {
	    	$check = $this->Comment->find('first', array(
	    		'conditions' => array(
	    			'Comment.created >= DATE_SUB(NOW(), INTERVAL '.$flood['SettingValue']['data'].' SECOND)',
	    			'OR' => array(
    					array('Comment.author_ip' => $_SERVER['REMOTE_ADDR']),
    					array('Comment.user_id' => $this->Auth->user('id'))
	    			)
	    		)
	    	));

	    	if ($check)
            {
	    		$time_diff = $flood['SettingValue']['data'] - (time() - strtotime($check['Comment']['created']));
	    		$message = 'You have reached the flood limit. Try again in another '.$time_diff.' seconds.';
	    	}
	    }

    	$this->Comment->create();

		$data = array('status' => false);

    	if (empty($message) && $this->Comment->save($this->request->data))
        {
	        if (!empty($this->request->data['ModuleValue']))
	        {
		        $this->loadModel('ModuleValue');
		        $this->ModuleValue->setModuleId($this->request->data['ModuleValue'], $this->Comment->id);
	        }

	        if (!empty($this->request->data['Comment']['author_name']))
		        $this->Session->write('Comment.author_name', $this->request->data['Comment']['author_name']);

	        if (!empty($this->request->data['Comment']['author_email']))
		        $this->Session->write('Comment.author_email', $this->request->data['Comment']['author_email']);

	        if (!empty($this->request->data['Comment']['author_website']))
		        $this->Session->write('Comment.author_website', $this->request->data['Comment']['author_website']);

	        $data['id'] = $this->Comment->id;
	        $data['status'] = true;
	        $data['message'] = $this->_getElement('Comments/ajax_post', array('status' => true));
    	} elseif (empty($message))
        {
    		$message = 'Your comment could not be posted at this time. Try again.';
		}

		if (empty($data['message']) && !empty($message))
			$data['message'] = $this->_getElement('Comments/ajax_post', array('status' => false, 'message' => $message));

		return $this->_ajaxResponse(array('body' => json_encode($data) ));
	}
}