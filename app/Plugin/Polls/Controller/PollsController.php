<?php

class PollsController extends PollsAppController {
	public $name = 'Polls';
	private $permissions;

	public function beforeFilter()
	{
		parent::beforeFilter();

		$this->permissions = $this->getPermissions();
	}

	public function admin_index()
	{
		$conditions = array();

		if (!isset($this->params->named['trash'])) {
	        $conditions['Poll.deleted_time'] = '0000-00-00 00:00:00';
	    } else {
	        $conditions['Poll.deleted_time !='] = '0000-00-00 00:00:00';
        }

	    if ($this->permissions['any'] == 0)
	    {
	    	$conditions['User.id'] = $this->Auth->user('id');
	    }

        $this->paginate = array(
            'order' => 'Poll.created DESC',
            'limit' => $this->pageLimit,
            'conditions' => $conditions,
            'contain' => array(
            	'User'
            )
        );
        
		$this->request->data = $this->paginate('Poll');
	}

	public function admin_add()
	{

		$this->set('articles', $this->Poll->Article->find('list'));

        if ($this->request->is('post')) {
        	$this->request->data['Poll']['user_id'] = $this->Auth->user('id');

            if ($this->Poll->saveAssociated($this->request->data)) {
                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Your poll has been added.', 'default', array('class' => 'alert alert-success'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to add your poll.', 'default', array('class' => 'alert alert-error'));
            }
        } 
	}

	public function admin_edit($id = null)
	{

		$this->Poll->id = $id;

	    if (!empty($this->request->data))
	    {
    		foreach($this->request->data['PollValue'] as $data) {
    			if (!empty($data['delete']) && $data['delete'] == 1) {
    				$this->Poll->PollValue->delete($data['id']);
    			} elseif (empty($data['id'])) {
    				$pollValue['PollValue'] = array(
    					'title' => $data['title'],
    					'plugin_poll_id' => $id
    				);
    				$this->Poll->PollValue->create();
    				$this->Poll->PollValue->save($pollValue);
    				unset($pollValue);
    			} else {
    				$this->Poll->PollValue->id = $data['id'];
    				$this->Poll->PollValue->saveField('title', $data['title']);
    			}
    		}
    		unset($this->request->data['PollValue']);

        	$this->request->data['Poll']['user_id'] = $this->Auth->user('id');

	        if ($this->Poll->save($this->request->data)) {
	            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Your poll has been updated.', 'default', array('class' => 'alert alert-success'));
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to update your poll.', 'default', array('class' => 'alert alert-error'));
	        }
	    }

        $this->request->data = $this->Poll->find('first', array(
        	'conditions' => array(
        		'Poll.id' => $id
        	),
        	'contain' => array(
    			'PollValue',
    			'User'
        	)
        ));
        $this->set('articles', $this->Poll->Article->find('list'));

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

	    $this->Poll->id = $id;

        $data = $this->Poll->find('first', array(
        	'conditions' => array(
        		'Poll.id' => $id
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
            $delete = $this->Poll->delete($id);
        } else {
        	$delete = $this->Poll->saveField('deleted_time', $this->Poll->dateTime());
        }

	    if ($delete) {
	        $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> The poll `'.$title.'` has been deleted.', 'default', array('class' => 'alert alert-success'));
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> The poll `'.$title.'` has NOT been deleted.', 'default', array('class' => 'alert alert-error'));
	        $this->redirect(array('action' => 'index'));
	    }
	}

    public function admin_restore($id = null, $title = null)
    {
        if ($this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $this->Poll->id = $id;

        $data = $this->Poll->find('first', array(
        	'conditions' => array(
        		'Poll.id' => $id
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

        if ($this->Poll->saveField('deleted_time', '0000-00-00 00:00:00')) {
            $this->Session->setFlash('The poll `'.$title.'` has been restored.', 'flash_success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The poll `'.$title.'` has NOT been restored.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }
    }

	public function vote()
	{
    	$this->layout = 'ajax';
    	$this->autoRender = false;

    	$id = $this->request->data['Poll']['id'];

        $count = $this->Poll->find('first', array(
            'conditions' => array(
                'Poll.id' => $id
            ),
            'contain' => array(
                'PollVotingValue' => array(
                    'conditions' => array(
                        'OR' => array(
                            'PollVotingValue.user_id' => $this->Auth->user('id'),
                            'PollVotingValue.user_ip' => $_SERVER['REMOTE_ADDR']
                        )
                    )
                )
            )
        ));

        if (count($count['PollVotingValue']) == 0) {
        	$this->Poll->id = $id;

        	$data = array(
        		'PollVotingValue' => array(
        			'plugin_poll_id' => $id,
        			'plugin_poll_value_id' => $this->request->data['Poll']['value'],
        			'user_id' => $this->Auth->user('id'),
        			'user_ip' => $_SERVER['REMOTE_ADDR']
        		)
        	);

        	$this->Poll->PollVotingValue->create();
        	if ($this->Poll->PollVotingValue->save($data)) {
        		$find = $this->Poll->find('first', array(
        			'conditions' => array(
        				'Poll.id' => $id
        			),
        			'contain' => array(
        				'PollValue'
        			)
        		));

        		$find['Poll']['total_votes'] = 0;
        		foreach($find['PollValue'] as $key => $row) {
        			if ($row['id'] == $this->request->data['Poll']['value']) {
        				$find['PollValue'][$key]['votes'] = $row['votes'] + 1;
        				$row['votes'] = $find['PollValue'][$key]['votes'];

		        		$this->Poll->PollValue->id = $row['id'];
		        		$this->Poll->PollValue->saveField('votes', $row['votes']);
        			}

        			$find['Poll']['total_votes'] = $find['Poll']['total_votes'] + $row['votes'];
        		}

        		$this->set('data', $find);

		    	$this->viewPath = 'Elements';
		    	$this->render('poll_vote_results');
        	}
        } else {
        	return json_encode(array(
        		'error' => true,
        		'message' => 'You have already voted on this poll'
        	));
        }

    	return json_encode($count);
	}

	public function ajax_results()
	{
    	$this->layout = 'ajax';
    	$this->autoRender = false;

		$find = $this->Poll->find('first', array(
			'conditions' => array(
				'Poll.id' => $this->request->data['Poll']['id']
			),
			'contain' => array(
				'PollValue'
			)
		));

		if (!empty($this->permissions['related']['polls']['vote']))
		{
			$find['Poll']['can_vote'] = $this->Poll->canVote($find, $this->Auth->user('id'));
		}

    	$this->set('data', $this->Poll->totalVotes($find));

    	$this->viewPath = 'Elements';
    	$this->render('poll_vote_results');	
	}

	public function ajax_view_poll()
	{
    	$this->layout = 'ajax';
    	$this->autoRender = false;

    	$conditions = array();

    	$conditions['Poll.id'] = $this->request->data['Poll']['id'];

	    if ($this->permissions['any'] == 0)
	    {
	    	$conditions['Poll.user_id'] = $this->Auth->user('id');
	    }

		$find = $this->Poll->find('first', array(
			'conditions' => $conditions,
			'contain' => array(
				'PollValue'
			)
		));

		if (!empty($this->permissions['related']['polls']['vote']))
		{
			$find['Poll']['can_vote'] = $this->Poll->canVote($find, $this->Auth->user('id'));
		}

        foreach($find['PollValue'] as $option) {
            $find['options'][$option['id']] = $option['title'];
        }

    	$this->set('data', $this->Poll->totalVotes($find));

    	$this->viewPath = 'Elements';
    	$this->render('poll_vote');			
	}
}