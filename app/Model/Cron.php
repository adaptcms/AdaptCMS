<?php

class Cron extends AppModel
{
	public $name = 'Cron';
	public $useTable = 'cron';

	public $belongsTo = array(
        'Components' => array(
            'className'    => 'Components',
            'foreignKey'   => 'component_id'
        )
    );
}