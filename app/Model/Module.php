<?php

class Module extends AppModel {
    public $name = 'Module';

    public $hasMany = array(
        'Block',
        'Cron',
        'Permission',
        'Field'
    );
}