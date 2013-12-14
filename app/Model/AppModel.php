<?php
App::uses('Model', 'Model');
/**
 * Class AppModel
 *
 * @method findById(integer $id)
 * @method findBySlug(string $slug)
 * @method findByTitle(string $title)
 */
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
     * @param null $time
     * @return datetime
     */
    public function dateTime($time = null)
    {
        return $time ? date('Y-m-d H:i:s', $time) : date('Y-m-d H:i:s');
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

    public function arrayKeyById($data = array(), $model)
    {
        $results = array();

        if (!empty($data))
        {
            foreach($data as $row)
            {
                if (!empty($row[$model]['id']))
                    $results[$row[$model]['id']] = $row[$model];
            }
        }

        return $results;
    }

    /**
    * After Save
    *
    * @param boolean $created
    *
    * @return void
    */
    public function afterSave($created)
    {
	    parent::afterSave($created);

        clearCache();
    }

    /**
     *
     */
    public function afterDelete()
    {
	    parent::afterDelete();

        clearCache();
    }

    /**
     * @param $dir
     * @return bool
     */
    public function recursiveDelete($dir) {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir) || is_link($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!$this->recursiveDelete($dir . "/" . $item)) {
                chmod($dir . "/" . $item, 0777);
                if (!$this->recursiveDelete($dir . "/" . $item)) return false;
            };
        }
        return rmdir($dir);
    }

    /**
     * Get Docs
     * Looks under View_Docs folder for documentation on tips/available data. Can used by plugins, theme, admin templates and normal templates.
     *
     * @param string $location Full absolute path to template
     * @param string $theme Only used if the template is in a theme
     * @param string $type View_Docs by default, this functionality could get expanded in the future
     *
     * @return string
     */
    public function getDocs($location, $theme = null, $type = 'View_Docs')
    {
        $contents = null;

        if ($theme)
        {
            $location = str_replace('Themed/' . $theme . '/', 'Themed/' . $theme . '/' . $type . '/', $location);
        }
        else
        {
            $location = str_replace('View/', $type . '/', $location);
        }

        $base_loc = basename($location);

        $files = array();
        if (strstr($location, 'app/Plugin'))
        {
            $files[] = $location;
            $files[] = str_replace($base_loc, 'glob.md', $location);
        }
        elseif ($theme)
        {
            $files[] = $location;
            $files[] = str_replace($base_loc, 'glob.md', $location);
            $files[] = str_replace('View/Themed/' . $theme . '/', '', $location);
            $files[] = str_replace('View/Themed/' . $theme . '/', '', str_replace($base_loc, 'glob.md', $location));

        }
        elseif (strstr($base_loc, 'admin_'))
        {
            $files[] = $location;
            $files[] = str_replace($base_loc, 'admin_glob.md', $location);
        }
        else
        {
            $files[] = $location;
            $files[] = str_replace($base_loc, 'glob.md', $location);
        }

        $match = null;
        foreach($files as $file)
        {
            if (empty($match))
            {
                $file = str_replace('ctp', 'md', $file);
                if (file_exists($file) && is_readable($file))
                    $match = file_get_contents($file);
            }
        }

        if (!empty($match))
        {
            App::import('Vendor', 'michelf/markdown/markdown');
            $contents = Markdown::defaultTransform($match);
        }

        return $contents;
    }

	/**
	 * Safe Html
	 *
	 * @param $string
	 * @return string
	 */
	public function safeHtml($string)
	{
		return strip_tags($string, '<p><br><b><strong><span><sup><sub><em><i><u><ul><li><ol><h1><h2><h3><h4>');
	}
}