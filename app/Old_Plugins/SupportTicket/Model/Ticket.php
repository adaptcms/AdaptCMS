<?php
App::uses('Sanitize', 'Utility');

class Ticket extends SupportTicketAppModel {
	public $name = 'PluginTicket';
	public $useTable = 'plugin_support_tickets';

	public $belongsTo = array(
		'User' => array(
			'className' => 'User'
		),
        'TicketCategory' => array(
            'className' => 'SupportTicket.TicketCategory',
            'foreignKey' => 'category_id'
        )
	);

    /**
    * Our validate rules
    */
    public $validate = array(
        'email' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter an email address'
            ),
            array(
                'rule' => 'email',
                'message' => 'Please enter a valid email address'
            )
        ),
        'message' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter in a message'
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
        if (!empty($this->data['Ticket']['subject']))
        {
            $this->data['Ticket']['slug'] = $this->slug($this->data['Ticket']['subject']);
        }
        
        $this->data = Sanitize::clean($this->data, array(
            'encode' => false,
            'remove_html' => true
        ));

        return true;
    }

    public function getReplyCount($data = array())
    {
        if (!empty($data))
        {
            foreach($data as $key => $row)
            {
                if (!empty($row['Ticket']['id']))
                {
                    $count = $this->find('count', array(
                        'conditions' => array(
                            'Ticket.parent_id' => $row['Ticket']['id']
                        )
                    ));
                    
                    $data[$key]['Ticket']['replies'] = $count;
                }
            }
        }

        return $data;
    }

    public function afterFind($results = array())
    {
        if (!empty($results))
        {
            foreach($results as $key => $result)
            {
                if (!empty($result['Ticket']))
                {
                    if ($result['Ticket']['parent_id'] == 0)
                    {
                        $results[$key]['reply'] = false;
                    }
                    else
                    {
                        $results[$key]['reply'] = true;
                    }
                }
            }
        }

        return $results;
    }
}