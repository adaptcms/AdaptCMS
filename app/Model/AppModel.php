<?php
App::uses('Model', 'Model');

class AppModel extends Model
{
    /**
     * Containable behavior a must
     * 
     * @var array
     */
    public $actsAs = array(
        'Containable'
    );
    
    /**
     * However with Containable, we define our contains manually
     * 
     * @var integer
     */
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

    /**
     * Returns datetime
     * 
     * @return datetime
     */
    public function dateTime()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * Based off of date, returns a model name string or array
     * 
     * @param array $data
     * @param boolean $array
     * @return mixed
     */
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

    /**
     * Slugs the string
     * 
     * @param string $str
     * @param boolean $orig
     * @return string
     */
    public function slug($str, $orig = null) {
        if (empty($orig)) {
            return strtolower(Inflector::slug($str, "-"));
        } else {
            return strtolower(Inflector::slug($str));
        }
    }
}