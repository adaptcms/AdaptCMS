<?php
/**
 * Class TicketsController
 */
class TicketsController extends SupportTicketAppController {
	public $name = 'Tickets';

    /**
    * In this beforeFilter we will get the permissions to be used in the view files
    *
    * Also set to the view a list of forum categories for category order
    */
	public function beforeFilter()
	{
		parent::beforeFilter();
	
		$this->permissions = $this->getPermissions();

		if ($this->params->action == 'add')
		{
	        $categories = $this->Ticket->TicketCategory->find('list', array(
	        	'conditions' => array(
	        		'TicketCategory.deleted_time' => '0000-00-00 00:00:00'
	        	),
	        	'order' => 'TicketCategory.title ASC'
	        ));

	        $this->set(compact('categories'));
		}

		$actions = array(
			'add',
			'reply',
			'view'
		);

		if (in_array($this->params->action, $actions))
		{
			if (!$this->Auth->user('id') && Configure::read('SupportTicket.captcha_for_guests'))
			{
				$this->captcha = true;

	    		$this->set('captcha', $this->captcha);
	    	}
		}
	}

	public function index()
	{
		$this->paginate = array(
            'order' => 'Ticket.created DESC',
            'contain' => array(
	        	'User',
	        	'TicketCategory'
        	),
            'conditions' => array(
            	'Ticket.deleted_time' => '0000-00-00 00:00:00',
            	'Ticket.parent_id' => 0
            ),
            'limit' => 10
        );
        
		$this->request->data = $this->Ticket->getReplyCount($this->paginate('Ticket'));

		$this->set('tickets', $this->request->data);
	}

	public function add()
	{
        if (!empty($this->request->data))
        {
        	$this->request->data['Ticket']['user_id'] = (!$this->Auth->user('id') ? 0 : $this->Auth->user('id'));
        	$this->request->data['Ticket']['parent_id'] = 0;

	        if (!empty($this->captcha))
	        {
                include_once(APP . 'webroot/libraries/captcha/securimage.php');
	            $securimage = new Securimage();

		        if (!empty($securimage) && 
		            !$securimage->check($this->request->data['captcha']))
		        {
		            $this->Session->setFlash('Incorrect captcha entred.', 'flash_error');
		            $error = true;
		        }
	        }

	        if (empty($error))
	        {
	            if ($this->Ticket->save($this->request->data))
	            {
			        $this->Session->setFlash('The ticket has been added.', 'flash_success');
			        $this->redirect(array(
			        	'action' => 'view',
			        	'id' => $this->Ticket->id,
			        	'slug' => $this->slug($this->request->data['Ticket']['subject'])
			        ));
			    } else {
			    	$this->Session->setFlash('The ticket has NOT been added.', 'flash_error');
			    }
			}
        }

        if ($email = $this->Auth->user('email'))
            $this->request->data['Ticket']['email'] = $email;
	}

	public function reply()
	{
        if (!empty($this->request->data))
        {
        	$this->request->data['Ticket']['user_id'] = (!$this->Auth->user('id') ? 0 : $this->Auth->user('id'));

            $this->Ticket->validator()->
                add('message', array(
                    array(
                        'rule' => 'notEmpty',
                        'message' => 'Please enter in a message'
                    )
                ));

	        if (!empty($this->captcha))
	        {
                include_once(APP . 'webroot/libraries/captcha/securimage.php');
	            $securimage = new Securimage();

		        if (!empty($securimage) && 
		            !$securimage->check($this->request->data['captcha']))
		        {
		            $this->Session->setFlash('Incorrect captcha entred.', 'flash_error');
		            $error = true;
		        }
	        }

	        if (empty($error))
	        {
	            if ($this->Ticket->save($this->request->data))
	            {
			        $this->Session->setFlash('Your reply has been added.', 'flash_success');
			    } else {
			    	$this->Session->setFlash('Your reply has NOT been added. Make sure to fill in all fields.', 'flash_error');
			    }

			    $redirect = true;
			}

			if (!empty($error) || !empty($redirect))
			{
		        $this->redirect(array(
		        	'action' => 'view',
		        	'id' => $this->request->data['Ticket']['parent_id'],
		        	'slug' => $this->request->data['Ticket']['slug']
		        ));
			}
        }
	}

	public function view($id = null)
	{
		if (empty($id) && !empty($this->params['id']))
		{
			$id = $this->params['id'];
		}

		$this->request->data = $this->Ticket->find('first', array(
            'contain' => array(
	        	'User',
	        	'TicketCategory'
        	),
            'conditions' => array(
            	'Ticket.deleted_time' => '0000-00-00 00:00:00',
            	'Ticket.id' => $id
            ),
		));

		$this->request->data['Replies'] = $this->Ticket->find('all', array(
            'contain' => array(
	        	'User'
        	),
            'conditions' => array(
            	'Ticket.deleted_time' => '0000-00-00 00:00:00',
            	'Ticket.parent_id' => $id
            ),
		));

		$this->set('ticket', $this->request->data);
	}
}