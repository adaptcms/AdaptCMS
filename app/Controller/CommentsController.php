<?php
App::import('Vendor', 'ayah');
App::import('Vendor', 'ayah_config');
App::import('Vendor', 'securimage');

class CommentsController extends AppController
{
	public $name = 'Comments';

    public function admin_edit($id = null)
    {
        if (!empty($this->request->data)) {
            unset($this->request->data['Comment']);
            
            if ($this->Comment->saveAll($this->request->data)) {
                // die(debug($this->request->data));
                $this->Session->setFlash('Comments have been updated.', 'flash_success');
                $this->redirect(
                    array(
                        'controller' => 'articles', 
                        'action' => 'edit', 
                        $id,
                        'comments'
                    )
                );
            } else {
                $this->Session->setFlash('Unable to update comments.', 'flash_error');
            }
        }
    }

	public function ajax_post()
	{
    	$this->layout = 'ajax';
    	$this->autoRender = false;

    	$this->request->data['Comment']['author_ip'] = $_SERVER['REMOTE_ADDR'];
    	$this->request->data['Comment']['active'] = 1;
    	$this->request->data['Comment']['created'] = date('Y-m-d H:i:s');

    	$this->loadModel('SettingValue');
    	$flood = $this->SettingValue->findByTitle('Comment Post Flood Limit');
        $captcha = $this->SettingValue->findByTitle('Comment Post Captcha Non-Logged In');

        $securimage = new Securimage();

        if (!empty($captcha['SettingValue']['data']) && $captcha['SettingValue']['data'] == 'Yes' && 
            !$this->Auth->user('id') && 
            !$securimage->check($this->request->data['Comment']['captcha'])) {
            $message = 'Invalid Captcha Answer. Please try again.';
        }

    	if (!empty($flood['SettingValue']['data']) && $flood['SettingValue']['data'] != 0) {
	    	$check = $this->Comment->find('first', array(
	    		'conditions' => array(
	    			'Comment.created >= DATE_SUB(NOW(), INTERVAL '.$flood['SettingValue']['data'].' SECOND)',
	    			'OR' => array(
    					array('Comment.author_ip' => $_SERVER['REMOTE_ADDR']),
    					array('Comment.user_id' => $this->Auth->user('id'))
	    			)
	    		)
	    	));

	    	if ($check) {
	    		$time_diff = $flood['SettingValue']['data'] - (time() - strtotime($check['Comment']['created']));
	    		$message = 'You have reached the flood limit. Try again in another '.$time_diff.' seconds.';
	    	}
	    }

    	if ($this->Auth->user('id')) {
    		$this->request->data['Comment']['user_id'] = $this->Auth->user('id');
    	}

    	$this->Comment->create();

    	if (empty($message) && $this->Comment->save($this->request->data)) {
    		return json_encode(array(
    			'status' => true,
    			'message' => '
    				<div id="flashMessage" class="alert alert-success">
    					<button class="close" data-dismiss="alert">×</button>
    					<strong>Success</strong> Your comment has been posted.
    				</div>',
    			'id' => $this->Comment->id
    		));
    	} elseif (empty($message)) {
    		$message = 'Your comment could not be posted at this time. Try again.';
    	}

		return json_encode(array(
			'status' => false,
			'message' => '
				<div id="flashMessage" class="alert alert-error">
					<button class="close" data-dismiss="alert">×</button>
					<strong>Error</strong> '.$message.'
				</div>'
		));
	}
}