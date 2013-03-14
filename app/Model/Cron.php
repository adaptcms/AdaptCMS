<?php

class Cron extends AppModel
{
    /**
    * Name of our Model, table will look like 'adaptcms_cron'
    */
	public $name = 'Cron';

	/**
	* Have to specify, otherwise it ends up being 'adaptcms_crons' - which sounds kinda weird, right?
	*/
	public $useTable = 'cron';

    /**
    * Every cron belongs to a module.
    */
	public $belongsTo = array(
        'Module' => array(
            'className'    => 'Module',
            'foreignKey'   => 'module_id'
        )
    );

    /**
    * Our validation rules
    */
    public $validate = array(
        'title' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Title cannot be empty'
            )
        ),
        'function' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Function name cannot be empty'
            )
        ),
        'period_amount' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Period Amount cannot be empty'
            )
        ),
        'period_type' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Period Type cannot be empty'
            )
        )
    );

    /**
    * This gets the period amount and type, then calculates the first run time of this cron entry.
    * The new run time is calculated on add and edit.
    */
    public function beforeSave()
    {
        if (!empty($this->data['Cron']['period_amount']))
        {
        	$amount = $this->data['Cron']['period_amount'];
        	$type = $this->data['Cron']['period_type'];
        	$this->data['Cron']['run_time'] = date('Y-m-d H:i:s', strtotime('+' . $amount . ' '.$type));
        }
        
    	return true;
    }
}