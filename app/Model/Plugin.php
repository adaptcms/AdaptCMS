<?php
/**
 * Class Plugin
 */
class Plugin extends AppModel
{
    public $name = 'Plugin';
    public $useTable = false;

    public function getActivePath()
    {
        return APP . 'Plugin' . DS;
    }

    public function getInactivePath()
    {
        return APP . 'Old_Plugins' . DS;
    }
}