<?php
App::uses('AppController', 'Controller');

class MessagesController extends AppController
{
    /**
    * Name of the Controller, 'Messages'
    */
	public $name = 'Messages';

	/**
	* List of box types, in the future this may be a database editable list
	*/
	private $boxes = array(
		'inbox',
		'outbox',
		'sentbox',
		'archive'
	);

	/**
	* This function returns a paginated list of messages based on which box the user is viewing.
	*
	* @param box_slug
	* @return associative array of messages
	*/
	public function index($box_slug = null)
	{
        $conditions['archive'] = array(
            'OR' => array(
                array(
                    'AND' => array(
                        'Message.sender_user_id' => $this->Auth->user('id'),
                        'Message.sender_archived_time !=' => '0000-00-00 00:00:00'
                    )
                ),
                array(
                    'AND' => array(
                        'Message.receiver_user_id' => $this->Auth->user('id'),
                        'Message.receiver_archived_time !=' => '0000-00-00 00:00:00'
                    )
                )
            )
        );

        $conditions['outbox'] = array(
            'Message.sender_archived_time' => '0000-00-00 00:00:00',
            'Message.sender_user_id' => $this->Auth->user('id'),
            'Message.is_read' => 0
        );

        $conditions['sentbox'] = array(
            'Message.sender_archived_time' => '0000-00-00 00:00:00',
            'Message.sender_user_id' => $this->Auth->user('id'),
            'Message.is_read' => 1
        );

        $conditions['inbox'] = array(
            'Message.receiver_archived_time' => '0000-00-00 00:00:00',
            'Message.parent_id' => 0,
            'Message.receiver_user_id' => $this->Auth->user('id')
        );

        $box_count = array();
        foreach($this->boxes as $box)
        {
            $box_count[$box] = $this->Message->find('count', array('conditions' => $conditions[$box]));
        }

        if (empty($box_slug))
            $box_slug = 'inbox';

		$this->paginate = array(
            'order' => 'Message.created DESC',
            'limit' => 10,
            'conditions' => $conditions[$box_slug],
            'contain' => array(
				'Receiver',
				'Sender'
			)
        );

		$this->request->data = $this->paginate('Message');

		$this->set('messages', $this->request->data);
		$this->set( 'box', $box_slug );
		$this->set( compact('box_count') );
	}

	/**
	* A thread view of messages sent between two users.
	*
	* @param id
	* @return subject
	* @return sender
	* @return messages list of messages in this thread
	*/
	public function view($id = null)
	{
		$this->request->data = $this->Message->find('all', array(
			'conditions' => array(
				'OR' => array(
					'Message.id' => $id,
					'Message.parent_id' => $id
				)
			),
			'contain' => array(
				'Receiver',
				'Sender'
			)
		));

		$messages = $this->request->data;

		if ($messages[0]['Message']['sender_user_id'] != $this->Auth->user('id') &&
			$messages[0]['Message']['receiver_user_id'] != $this->Auth->user('id'))
		{
			$this->redirect(array('action' => 'index'));
		}

		foreach($messages as $row)
		{
			if ($row['Message']['is_read'] == 0 && $row['Receiver']['id'] == $this->Auth->user('id'))
			{
				$this->Message->id = $row['Message']['id'];
				$this->Message->saveField('is_read', 1);
			}
		}

		$this->set('subject', $messages[0]['Message']['title']);
		$this->set('sender', $messages[0]['Sender']['id']);
		$this->set(compact('messages'));
	}

	/**
	* AJAX Functionality and non-ajax to create a new message or ajax replying to one.
	*
	* @return json_encode array if AJAX request, otherwise flash/redirect
	*/
	public function send()
	{
		if (!empty($this->request->data))
		{
			$this->request->data['Message']['sender_user_id'] = $this->Auth->user('id');

			if ($this->request->is('ajax'))
            {
		    	$this->layout = 'ajax';
		    	$this->autoRender = false;

		    	$this->Message->updateAll(
		    		array(
		    			'Message.last_reply_time' => '"' . $this->Message->dateTime() . '"'
		    		),
		    		array(
		    			'OR' => array(
		    				'Message.id' => $this->request->data['Message']['parent_id'],
		    				'Message.parent_id' => $this->request->data['Message']['parent_id']
		    			)
		    		)
		    	);
			}

            if ($this->Message->save($this->request->data))
            {
            	if ($this->layout == 'ajax')
            	{
            		return json_encode(array(
            			'status' => true
            		));
            	} else {
	                $this->Session->setFlash('Your message has been sent.', 'flash_success');
	                $this->redirect(array('action' => 'index', 'outbox'));
	            }
            } else {
            	if ($this->layout == 'ajax')
            	{
            		return json_encode(array(
            			'status' => false
            		));
            	} else {
                	$this->Session->setFlash('Unable to send message. Fix the errors below.', 'flash_error');
               	}
            }
		}
	}

	/**
	* This function handles changing the current box of a message, marking it read and related functionality.
	*
	* @param action is either moving the message to archive, inbox or marking it read
	* @param id of messages
	* @return redirect and flash message
	*/
	public function move($action = null, $id = null)
	{
		$this->request->data = $this->Message->findById($id);

		if ($this->request->data['Message']['sender_user_id'] != $this->Auth->user('id') &&
			$this->request->data['Message']['receiver_user_id'] != $this->Auth->user('id'))
		{
			$this->redirect(array('action' => 'index'));
		}

		$this->Message->id = $id;

		if ($this->request->data['Message']['receiver_user_id'] == $this->Auth->user('id') && $action == "archive")
		{
			$save = $this->Message->saveField('receiver_archived_time', $this->Message->dateTime());
			$msg = 'archived';
			$box = 'archive';

		} elseif($this->request->data['Message']['receiver_user_id'] == $this->Auth->user('id') && $action == "inbox")
		{
			$save = $this->Message->saveField('receiver_archived_time', '0000-00-00 00:00:00');
			$msg = 'moved to the inbox';
			$box = 'inbox';

		} elseif ($this->request->data['Message']['sender_user_id'] == $this->Auth->user('id') && $action == "archive")
		{
			$save = $this->Message->saveField('sender_archived_time', $this->Message->dateTime());
			$msg = 'archived';
			$box = 'archive';

		} elseif($this->request->data['Message']['sender_user_id'] == $this->Auth->user('id') && $action == "inbox")
		{
			$save = $this->Message->saveField('sender_archived_time', '0000-00-00 00:00:00');
			$msg = 'moved to the inbox';
			$box = 'inbox';

		} elseif($this->request->data['Message']['receiver_user_id'] == $this->Auth->user('id') && $action == "mark_read")
		{
			$save = $this->Message->saveField('is_read', '1');
			$msg = 'marked read';
			$box = 'inbox';
		}

        if ($save) {
            $this->Session->setFlash('The message has been ' . $msg . '.', 'flash_success');
            $this->redirect(array('action' => 'index', $box));
        } else {
            $this->Session->setFlash('The message could not be ' . $msg . '.', 'flash_error');
            $this->redirect(array('action' => 'index', $box));
        }
	}
}