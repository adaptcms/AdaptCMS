<?php
App::uses('ContactFormAppModel', 'ContactForm.Model');
/**
 * Class ContactForm
 */
class ContactForm extends ContactFormAppModel
{
	public $useTable = false;

    public $validate = array(
        'name' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter in your name'
            )
        ),
	    'email' => array(
		    array(
			    'rule' => 'notEmpty',
			    'message' => 'Please enter in your email'
		    ),
		    array(
			    'rule' => array('email', true),
			    'message' => 'Please supply a valid email address.'
		    )
	    ),
	    'message' => array(
		    array(
			    'rule' => 'notEmpty',
			    'message' => 'Please enter in your message'
		    )
	    )
    );
}