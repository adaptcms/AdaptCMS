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

        return true;
    }
}