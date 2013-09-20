<?php
/**
 * Class AdminHelper
 *
 * @property SessionHelper $Session
 * @property HtmlHelper $Html
 * @property TimeHelper $Time
 */
class AdminHelper extends AppHelper
{
    /**
     * Name of the helper
     * 
     * @var string
     */
    public $name = 'Admin';
    
    /**
     * List of helpers that these functions use
     * 
     * @var array 
     */
    public $helpers = array(
        'Html',
        'Session',
        'Time'
    );

    /**
     * Returns current datetime
     * 
     * @return datetime
     */
    public function datetime()
    {
        return $this->Time->format('Y-m-d H:i:s', time());
    }

    /**
     * Generates edit link for admin
     * 
     * @param integer $id
     * @param string $controller
     * @param string $title
     * @param string $action
     * @param string $param
     * @return string HTML Link with icon/text
     */
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

    /**
     * Generates delete link for admin
     * 
     * @param integer $id
     * @param string $title
     * @param string $text
     * @param string $controller
     * @param string $action
     * @return string HTML Link with icon/text
     */
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

    /**
     * Generates restore link for admin
     * 
     * @param integer $id
     * @param string $title
     * @param string $controller
     * @return string HTML Link with icon/text
     */
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

    /**
     * Generates delete link for admin
     * 
     * @param integer $id
     * @param string $title
     * @param string $text
     * @param string $controller
     * @return string HTML Link with icon/text
     */
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
                'onclick' => "return confirm('Are you sure you want to delete this " . $text . "? This is permanent.')"
            )
        );
    }

	/**
	 * Generates view link for admin
	 *
	 * @param integer $id
	 * @param string $action
	 * @param string $controller
	 * @param array $params
	 * @return string HTML Link with icon/text
	 */
    public function view($id = null, $action = 'view', $controller = null, $params = array())
    {
        $vars = array(
            'action' => !empty($action) ? $action : 'view',
            'admin' => false,
            'controller' => (!empty($controller) ? $controller : $this->params->controller)
        );

        if (!empty($id) && empty($params))
        {
            $vars[] = $id;
        }
        else
        {
            $vars = array_merge($vars, $params);
        }

        return $this->Html->link('<i class="icon-picture"></i> View', $vars,
            array(
                'escape' => false
            )
        );
    }

    /**
     * Checks session to see if user is logged in. Returns true or false.
     * 
     * @return boolean
     */
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

    /**
     * If user is logged in and has timezone, uses that to calculate time. Otherwise returns it based on
     * server time.
     * 
     * @param integer $time
     * @param string $format
     * @return string
     */
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

    /**
     * Convenience function that based on permissions array/user_id, checks to see if user
     * has access to item. Returns true or false.
     * 
     * @param array $permission
     * @param integer $user_id
     * @return boolean
     */
    public function hasPermission($permission = null, $user_id = null)
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

	/**
	 * Is Active
	 * Convenience function checking deleted_time (if exists) and status (if exists) to see
	 * if item is allowed to be viewed on frontend of site.
	 *
	 * @param array $data
	 * @param string $model
	 * @return boolean
	 */
    public function isActive($data = array(), $model)
    {
        if (!empty($data) && !empty($model))
        {
            if (isset($data[$model]['deleted_time']) && $data[$model]['deleted_time'] == '0000-00-00 00:00:00')
            {
                if (!isset($data[$model]['status']) || isset($data[$model]['status']) && $data[$model]['status'] == 1)
                {
                    return true;
                }
            }
            elseif (!isset($data[$model]['deleted_time']))
            {
                return true;
            }
        }

        return false;
    }

    /**
    * Remove
     * Generates delete link for admin
     * 
     * @param integer $id
     * @param string $title
     * @param boolean $permanent
     * @param string $controller
     * @param string $action
     * @return string HTML Link with icon/text
     */
    public function remove($id, $title, $permanent = false, $controller = null, $action = null)
    {
        $text = ($permanent ? 'Delete Forever' : 'Delete');
        $full_text = ($permanent ? ' This is permanent.' : '');

        return $this->Html->link('<i class="icon-trash"></i> ' . $text, array(
                'action' => (!empty($action) ? $action : 'delete'),
                'controller' => (!empty($controller) ? $controller : $this->params->controller),
                $id, 
                $title
            ),
            array(
                'escape' => false, 
                'onclick' => 
                    "return confirm('Are you sure you want to delete this item?" . $full_text . "')"
            )
        );
    }
}