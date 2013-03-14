<?php

class AdminHelper extends AppHelper
{
	public $helpers = array(
		'Html',
		'Session',
		'Time'
	);

	public function datetime()
	{
		return $this->Time->format('Y-m-d H:i:s', time());
	}

	public function edit($id, $controller = null, $title = null, $action = null, $param = null)
	{
		if (empty($title))
		{
			$title = '<i class="icon-pencil"></i> Edit';
		}
		
		return $this->Html->link($title, array(
			'controller' => (!empty($controller) ? $controller : $this->params->controller),
			'action' => (!empty($action) ? $action : 'edit'),
			$id,
			(!empty($param) ? $param : '')
			),
            array(
            	'escape' => false
            )
        );
	}

	public function delete($id, $title, $text, $controller = null, $action = null)
	{
		return $this->Html->link('<i class="icon-trash"></i> Delete', array(
			'action' => (!empty($action) ? $action : 'delete'),
			'controller' => (!empty($controller) ? $controller : $this->params->controller),
			$id, 
			$title
			),
            array(
            	'escape' => false, 
            	'onclick' => 
            		"return confirm('Are you sure you want to delete this " . $text . "?')"
            )
        );
	}

	public function restore($id, $title, $controller = null)
	{
		return $this->Html->link('<i class="icon-share-alt"></i> Restore', array(
			'action' => 'restore', 
			'controller' => (!empty($controller) ? $controller : $this->params->controller),
			$id, 
			$title
			),
            array(
            	'escape' => false
            )
        );
	}

	public function delete_perm($id, $title, $text, $controller = null)
	{
		return $this->Html->link('<i class="icon-trash"></i> Delete Forever', array(
			'action' => 'delete',
			'controller' => (!empty($controller) ? $controller : $this->params->controller), 
			$id, 
			$title, 
			1
			),
            array(
            	'escape' => false, 
            	'onclick' => 
            		"return confirm('Are you sure you want to delete this " . $text . "? This is permanent.')"
        	)
        );
	}

	public function view($id, $action = 'view', $controller = null)
	{
		return $this->Html->link('<i class="icon-picture"></i> View', array(
			'action' => $action,
			'admin' => false,
			'controller' => (!empty($controller) ? $controller : $this->params->controller),
			$id
			),
			array(
				'escape' => false
			)
		);
	}

	public function isLoggedIn()
	{
		if ($this->Session->check('Auth.User.id'))
		{
			return true;
		} 
		else
		{
			return false;
		}
	}

	public function time($time, $format = 'F jS, Y h:i A')
	{
		if ($this->isLoggedIn())
		{
			$settings = json_decode(
	            $this->Session->read('Auth.User.settings'),
	            true
	        );

	        if (!empty($settings['time_zone']))
	        {
	        	$timezone = $settings['time_zone'];
	    	}
	    }

	    if ($format == 'words')
	    {
			return $this->Time->timeAgoInWords(
				$time,
	            array(
	            	'format' => 'F jS, Y',
	            	'timezone' => (!empty($timezone) ? $timezone : null),
	            	'end' => '+1 month' 
	            )
	        );
	    } else {
			return $this->Time->format(
	            $format, 
	            $time,
	            false,
	            (!empty($timezone) ? $timezone : null)
	        );
		}
	}

	public function hasPermission($permission, $user_id = null)
	{
		if (!empty($permission) && !empty($user_id) && $user_id == $this->Session->read('Auth.User.id') || 
			!empty($permission) && $permission['any'] > 0 ||
			!empty($permission) && $permission['action'] == 'admin_add' && $permission['any'] == 0 ||
			!empty($permission) && $permission['any'] == 2)
		{
			return true;
		} else {
			return false;
		}
	}
}