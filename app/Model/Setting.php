<?php

class Setting extends AppModel
{
    /**
    * Name of our Model, table will look like 'adaptcms_settings'
    */
	public $name = 'Setting';

	/**
	* Validation rules for Setting Categories, title must have a value and must be unique
	*/
    public $validate = array(
    	'title' => array(
			array(
				'rule' => 'notEmpty',
				'message' => 'Setting title cannot be empty'
			),
			array(
				'rule' => 'isUnique',
				'message' => 'Setting title has already been used'
			)
        )
    );

    /**
    * One to Many relationship, setting categories have many values
    */
    public $hasMany = array(
    	'SettingValue' => array(
            'dependent' => true
        )
    );

    /**
    * Cleans category title of HTML
    */
    public function beforeSave()
    {
        if (!empty($this->data['Setting']['title']))
        {
            $this->data['Setting']['title'] = strip_tags($this->data['Setting']['title']);
        }

        return true;
    }
}