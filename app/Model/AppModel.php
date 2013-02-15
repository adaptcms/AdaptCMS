<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model
{
    public $actsAs = array('Containable');
    public $recursive = -1;

    /*
     * Source: Snipplr
     * http://snipplr.com/view/51108/nested-array-search-by-value-or-key/
    */
    function searchArray(array $array, $search, $mode = 'value') {
        foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($array)) as $key => $value) {
            if ($search === ${${"mode"}})
                return true;
        }
        return false;
    }

    public function dateTime()
    {
        return date('Y-m-d H:i:s');
    }

    public function loadModelName($data, $array = false)
    {
        if ( empty($data) )
        {
            return false;
        }

        if ( $data['Module']['is_plugin'] == 1 )
        {
            $model = str_replace( ' ', '', $data['Module']['title'] ) . '.' . $data['Module']['model_title'];
        } else {
            $model = $data['Module']['model_title'];
        }

        if ( !$array )
        {
            return $model;
        } else {
            return array(
                'load' => $model,
                'name' => $data['Module']['model_title']
            );
        }
    }

    public function slug($title)
    {
        return strtolower(Inflector::slug($title, '-'));
    }
}