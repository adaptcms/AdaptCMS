<?php

class Cron extends AppModel
{
	public $name = 'Cron';
	public $useTable = 'cron';

	public $belongsTo = array(
        'Module' => array(
            'className'    => 'Module',
            'foreignKey'   => 'module_id'
        )
    );
}