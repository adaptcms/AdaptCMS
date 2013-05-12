<?php

class SettingValue extends AppModel 
{
    /**
    * Name of our Model, table will look like 'adaptcms_setting_values'
    */
	public $name = 'SettingValue';

	/**
	* All Setting Values belong to a setting (also known as a 'Setting Category')
	*/
	public $belongsTo = array(
        'Setting' => array(
            'className'    => 'Setting',
            'foreignKey'   => 'setting_id'
        )
    );

    /**
    * Validation rules - title must have a value
    */
    public $validate = array(
        'title' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Title cannot be empty'
            )
        )
    );

    public $multi_data_types = array(
        'check',
        'multi-dropdown'
    );

    /**
    * Field data options are transformed and if a user flags a setting to be deleted
    * then the deleted time is set.
    *
    * @return true
    */
    public function beforeSave()
    {
        if (!empty($this->data['FieldData'])) {
            $this->data['SettingValue']['data_options'] = 
                str_replace("'","",json_encode($this->data['FieldData']));
        }

        if (!empty($this->data['SettingValue']['deleted']))
        {
            $this->data['SettingValue']['deleted_time'] = $this->dateTime();
        }

        if (!empty($this->data['SettingValue']['title']))
        {
            $this->data['SettingValue']['title'] = strip_tags($this->data['SettingValue']['title']);
        }

        if (!empty($this->data['SettingValue']['data']) && is_array($this->data['SettingValue']['data']))
        {
            $this->data['SettingValue']['data'] = json_encode($this->data['SettingValue']['data']);
        }

        return true;
    }

    /**
    * Goes through setting values and json_decodes data_options if applicable and if setting value type
    * is of a multi-data (meaning its contents gets json_encoded), then json_decode it into an array.
    *
    * @param results
    * @return array of filtered data
    */
    public function afterFind($results)
    {
        if (!empty($results))
        {
            foreach($results as $key => $result)
            {
                if (!empty($result['SettingValue']['data_options']))
                {
                    $results[$key]['SettingValue']['data_options'] = json_decode($result['SettingValue']['data_options'], true);
                }

                if (!empty($result['SettingValue']['data']) && !empty($result['SettingValue']['setting_type']) && 
                    in_array($result['SettingValue']['setting_type'], $this->multi_data_types))
                {
                    $results[$key]['SettingValue']['data'] = json_decode($result['SettingValue']['data'], true);
                }
            }

            return $results;
        }

        return array();
    }
}