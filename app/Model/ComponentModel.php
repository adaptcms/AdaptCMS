<?php

class ComponentModel extends AppModel {
	public $name = "Component";

	public $hasMany = array('Module');
}