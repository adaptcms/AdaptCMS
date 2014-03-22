<?php
App::uses('AppController', 'Controller');
/**
 * Class LinksAppController
 */
class LinksAppController extends AppController
{
	public $name = 'LinksAppController';

	/*
	 * Put in an array, the allowed actions. This overrides the permission settings and
	 * is applied for all roles/users
	 */
	public $allowedActions = array(
		'track'
	);
}