<?php

class TicketsController extends SupportTicketAppController {
	public $name = 'SupportTicket.Tickets';
	public $uses = array('SupportTicket.Ticket');

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout('admin', 'support_ticket');
	}

	public function index()
	{
		$this->paginate = array(
            'order' => 'Ticket.created DESC',
            'contain' => array(
	        	'SendUser', 
	        	'ReplyUser'
        	),
            'conditions' => array(
            	'Ticket.deleted_time' => '0000-00-00 00:00:00'
            ),
            'limit' => 10
        );
        
		$this->request->data = $this->paginate('Ticket');
	}

	public function add()
	{
        if ($this->request->is('post')) {
        		$this->request->data['Ticket']['send_user_id'] = $this->Auth->user('id');

            if ($this->Ticket->save($this->request->data)) {
                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Your ticket has been added.', 'default', array('class' => 'alert alert-success'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to add your ticket.', 'default', array('class' => 'alert alert-error'));
            }
        } 		
	}

	public function reply()
	{

	}

	public function view()
	{

	}

}