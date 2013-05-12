<?php
App::uses('Sanitize', 'Utility');

class TicketCategory extends SupportTicketAppModel {
	public $name = 'PluginTicketCategory';
	public $useTable = 'plugin_support_categories';

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

	public $hasMany = array(
        'Ticket' => array(
            'className' => 'SupportTicket.Ticket'
        )
	);

    /**
    * Our validate rules. The Category title must not be empty and must be unique.
    */
    public $validate = array(
        'title' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Category title cannot be empty'
            ),
            array(
                'rule' => 'isUnique',
                'message' => 'Category title has already been used'
            )
        )
    );
    
    /**
    * Sets the slug
    *
    * @return true
    */
    public function beforeSave()
    {
        if (!empty($this->data['TicketCategory']['title']))
        {
            $this->data['TicketCategory']['slug'] = $this->slug($this->data['TicketCategory']['title']);
        }

        $this->data = Sanitize::clean($this->data, array(
            'encode' => false,
            'remove_html' => true
        ));

        return true;
    }
}