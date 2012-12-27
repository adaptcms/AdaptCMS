<?php

class Components extends AppModel {
	public $name = 'Component';

	public $hasMany = array(
		'Module',
		'Cron'
	);
}