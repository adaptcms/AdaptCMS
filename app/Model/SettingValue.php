<?php

class SettingValue extends AppModel {
	public $name = 'SettingValue';
	public $belongsTo = array(
        'Setting' => array(
            'className'    => 'Setting',
            'foreignKey'   => 'setting_id'
        )
    );
}