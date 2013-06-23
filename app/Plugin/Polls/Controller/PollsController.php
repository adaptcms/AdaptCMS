<?php
App::uses('AppController', 'Controller');
/**
 * Class PollsController
 * @property Poll $Poll
 * @property paginate $paginate
 * @property pageLimit $pageLimit
 */
class PollsController extends PollsAppController
{
    /**
    * Name of the Controller, 'Polls'
    */
	public $name = 'Polls';

    /**
    * array of permissions for this page
    */
	private $permissions;

    /**
    * In this beforeFilter we will get the permissions to be used in the view files
    */
	public function beforeFilter()
	{
		parent::beforeFilter();

		$this->permissions = $this->getPermissions();
	}

    /**
    * Returns a paginated index of Polls
    *
    * @return array of polls data
    */
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

    /**
    * Returns nothing before post
    *
    * On POST, returns error flash or success flash and redirect to index on success
    *
    * @return mixed
    */
	public function admin_add()
	{
		$this->set('articles', $this->Poll->Article->find('list'));

        if (!empty($this->request->data))
        {
        	$this->request->data['Poll']['user_id'] = $this->Auth->user('id');

            if ($this->Poll->saveAssociated($this->request->data))
            {
                $this->Session->setFlash('Your poll has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your poll.', 'flash_error');
            }
        } 
	}

    /**
    * Before POST, sets request data to form
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param id ID of the database entry, redirect to index if no permissions
    * @return associative array of poll data
    */
	public function admin_edit($id = null)
	{
		$this->Poll->id = $id;

	    if (!empty($this->request->data))
	    {
    		foreach($this->request->data['PollValue'] as $key => $row)
            {
                if (!empty($row['delete']) && !empty($row['id']))
                {
                    unset($this->request->data['PollValue'][$key]);
                    $this->Poll->PollValue->delete($row['id']);
                }
            }

        	$this->request->data['Poll']['user_id'] = $this->Auth->user('id');

	        if ($this->Poll->saveAssociated($this->request->data))
            {
                $this->Session->setFlash('Your poll has been updated.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to update your poll.', 'flash_error');
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

    /**
     * If item has no delete time, then initial deletion is to the trash area (making it in-active on site, if applicable)
     *
     * But if it has a deletion time, meaning it is in the trash, deleting it the second time is permanent.
     *
     * @param integer $id of the database entry, redirect to index if no permissions
     * @param string $title of this entry, used for flash message
     * @param boolean $permanent
     * @internal param \If $permanent not NULL, this means the item is in the trash so deletion will now be permanent
     * @return redirect
     */
	public function admin_delete($id = null, $title = null, $permanent = null)
	{
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

        if (!empty($permanent))
        {
            $delete = $this->Poll->delete($id);
        } else {
        	$delete = $this->Poll->saveField('deleted_time', $this->Poll->dateTime());
        }

	    if ($delete) {
            $this->Session->setFlash('Your poll `' . $title . '` has been deleted.', 'flash_success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('Unable to delete your poll `' . $title . '`.', 'flash_error');
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
            $this->Session->setFlash('Your poll `' . $title . '` has been restored.', 'flash_success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('Unable to restore your poll.', 'flash_error');
        }
    }

    /**
    * Action to vote on a poll
    */
	public function vote()
	{
    	$this->layout = 'ajax';
    	$this->autoRender = false;

    	$id = $this->request->data['Poll']['id'];

        $conditions = array(
            'conditions' => array(
                'PollVotingValue.plugin_poll_id' => $id
            )
        );

        if ($this->Auth->user('id'))
        {
            $conditions['conditions']['OR'] = array(
                'PollVotingValue.user_id' => $this->Auth->user('id'),
                'PollVotingValue.user_ip' => $_SERVER['REMOTE_ADDR']
            );
        }
        else
        {
            $conditions['conditions']['PollVotingValue.user_ip'] = $_SERVER['REMOTE_ADDR'];
        }

        $count = $this->Poll->PollVotingValue->find('count', $conditions);

        if ($count == 0) {
        	$this->Poll->id = $id;

        	$data = array(
        		'PollVotingValue' => array(
        			'plugin_poll_id' => $id,
        			'plugin_value_id' => $this->request->data['Poll']['value'],
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
        	}
        } else {
        	$this->set('error', 'You have already voted on this poll');
        }

        if (empty($find))
            $find = $this->Poll->find('first', array(
                'conditions' => array(
                    'Poll.id' => $id
                ),
                'contain' => array(
                    'PollValue'
                )
            ));

        $this->set('data', $find);

        $this->viewPath = 'Elements';
        $this->render('Polls.poll_vote_results');
	}

    /**
    * Passed poll data and renders vote results element
    */
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
			$find['Poll']['can_vote'] = $this->Poll->canVote($find, $this->Auth->user('id'));

        $data = $this->Poll->totalVotes($find);

        if (!empty($this->request->data['Block']['title']))
        {
            $data['Block']['title'] = $this->request->data['Block']['title'];
        }

    	$this->set(compact('data'));

    	$this->viewPath = 'Elements';
    	$this->render('Polls.poll_vote_results');
	}

    /**
    * View poll
    */
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

        $data = $this->Poll->totalVotes($find);

        if (!empty($this->request->data['Block']['title']))
        {
            $data['Block']['title'] = $this->request->data['Block']['title'];
        }

        $this->set(compact('data'));

    	$this->viewPath = 'Elements';
    	$this->render('Polls.poll_vote');
	}
}