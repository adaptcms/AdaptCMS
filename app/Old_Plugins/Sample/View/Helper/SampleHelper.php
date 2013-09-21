<?php

class SampleHelper extends AppHelper
{
    public $name = 'Sample';

    public $helpers = array('Html');

    /**
    * Test Helper
    * This simply returns a linked html element to google.com
    *
    * @return string
    */
    public function test()
    {
    	return $this->Html->url('http://www.google.com');
    }
}