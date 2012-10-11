<?php

class Module extends AppModel
{
	public $name = 'Module';

	public $belongsTo = array(
		'Template' => array(
			'className' => 'Template',
			'foreignKey' => 'template_id'
			)
	);
}