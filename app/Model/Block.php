<?php
/**
 * Class Block
 *
 * @property Module $Module
 * @property User $User
 */
class Block extends AppModel
{
	/**
    * Name of our Model, table will look like 'adaptcms_blocks'
    */
    public $name = 'Block';

    /**
    * Our relationships, currently to module and user
    */
	public $belongsTo = array(
		'Module' => array(
			'className' => 'Module',
			'foreignKey' => 'module_id'
		),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
	);

	/**
	 * @var array
	 */
	public $actsAs = array('Delete');

    /**
    * Validation Rules. Title must not be empty and must be unique.
    */
    public $validate = array(
    	'title' => array(
			array(
				'rule' => 'notEmpty',
				'message' => 'Block title cannot be empty.'
			),
			array(
				'rule' => 'isUnique',
				'message' => 'Block title is already in use.'
			)
        )
    );

    /**
    * Array of Block Types - Code (for custom php and html code), Dynamic (used with Modules) and Text
    */
    public $block_types = array(
        'code' => 'Code Block',
        'dynamic' => 'Dynamic Data Block',
        'text' => 'Text Block'
    );

    /**
    * This is called when editing a block in the admin area, formatting the data
    * so that the form gets all the data appropiately.
    *
    * @param $data array of Block Data
    * @return array Array of formatted data
    */
    public function formatData($data)
    {
        if ($data['Block']['type'] == "dynamic")
        {
            if ($data['Module']['is_plugin'] == 1)
            {
                $data['Block']['model'] = 
                    $data['Module']['title'] . '.' . $data['Module']['model_title'];
            } else {
                $data['Block']['model'] = $data['Module']['model_title'];
            }
        }
        
        if (!empty($data['Block']['settings'])) {
            $settings = json_decode($data['Block']['settings']);

	        if (!empty($settings)) {
	            foreach($settings as $key => $row) {
	                $data['Block'][$key] = $row;
	                if ($key != "order_by" && $key != "order_dir") {
	                    $data['Block']['settings_keys'][] = $key;
	                }
	            }
	        }
        }

        if ($data['Block']['type'] == "code")
        {
            $data['Block']['code'] = $data['Block']['data'];
        } elseif($data['Block']['type'] == "text")
        {
            $data['Block']['text'] = $data['Block']['data'];
        }

        return $data;
    }

    /**
    * Before Saving, we slug the title and format any extra options and miscelaneous options.
    *
    * @param $options array
    * @return boolean
    */
    public function beforeSave($options = array())
    {
        if (!empty($this->data['Block']['title']))
        {
            $type = $this->data['Block']['type'];

            $this->data['Block']['title'] = $this->slug($this->data['Block']['title']);
            
            if (!empty($this->data['Block']['order_by'])) {
                $this->data['Block']['settings']['order_by'] = $this->data['Block']['order_by'];
            }

            if (!empty($this->data['Block']['order_dir'])) {
                $this->data['Block']['settings']['order_dir'] = $this->data['Block']['order_dir'];
            }

            if (!empty($this->data['Block']['model']) && $type == "dynamic") {
                if (strstr($this->data['Block']['model'], '.')) {
                    $ex = explode('.', $this->data['Block']['model']);

                    $get = $this->Module->find('first', array(
                        'conditions' => array(
                            'Module.is_plugin' => 1,
                            'Module.title' => $ex[0],
                            'Module.model_title' => $ex[1]
                        )
                    ));
                } else {
                    $get = $this->Module->findByModelTitle($this->data['Block']['model']);
                }

                $this->data['Block']['module_id'] = $get['Module']['id'];
            } elseif ($type == "code" && !empty($this->data['Block']['code'])) {
                $this->data['Block']['data'] = $this->data['Block']['code'];
            } elseif ($type == "text" && !empty($this->data['Block']['text'])) {
                $this->data['Block']['data'] = $this->data['Block']['text'];
            }

            if (!empty($this->data['Block']['settings'])) {
                $this->data['Block']['settings'] = json_encode($this->data['Block']['settings']);
            }
        }

        return true;
    }

    /**
    * This function retrieves all active modules into a list to be used in a dropdown
    *
    * @return array List of modules
    */
    public function getModules()
    {
        $module_list = $this->Module->find('all', array(
            'conditions' => array(
                'Module.block_active' => 1
            )
        ));

        $models = array();
        foreach ($module_list as $row)
        {
            if ($row['Module']['is_plugin'] == 1)
            {
                $models[$row['Module']['title'].'.'.$row['Module']['model_title']] = "Plugin - ".$row['Module']['title'];
            } else {
                $models[$row['Module']['model_title']] = $row['Module']['title'];
            }
        }

        return $models;
    }
}