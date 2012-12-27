<?php

class Theme extends AppModel{
	public $name = 'Theme';
	public $hasMany = array('Template');

	public $validate = array(
      'title' => array(
	      array(
	        'rule' => 'notEmpty',
	        'message' => 'Please enter theme name'
	      ),
	      array(
	        'rule' => 'isUnique',
	        'message' => 'Theme name already in use'
	      )
	    )
    );

	public function camelCase($string, $spaces = null) 
	{ 
	  	$string = ucwords(str_replace(array('-', '_'), ' ', $string));

	  	if (!$spaces) {
	  		return str_replace(' ', '', $string);
	  	} else {
	  		return $string;
		}
	}

	public function assetsList($id = null, $theme = null)
	{
        if (empty($id) || $id == 1) {
            $path = WWW_ROOT;
        } else {
            $path = WWW_ROOT.'themes/'.$theme.'/';
        }

        $exclude = array(".", "themes", ".htaccess", "index.php", "uploads");
        $exclude2 = array("..", "fancybox", "tiny_mce");

        if ($dh = opendir($path)) {
            while (($file = readdir($dh)) !== false) {
                if (!in_array($file, $exclude) && $file != ".." && $file != ".") {
                    if (is_dir($path.$file) && $fol = opendir($path.$file)) {
                        while(($row = readdir($fol)) != false) {
                            if ($row != ".." && $row !="." && !in_array($row, $exclude2)) {
                                if ($file != ".") {
                                    $data[$file][] = $row;
                                } else {
                                    $data[] = $row;
                                }
                            }
                        }
                    } else {
                    	$data[] = $file;
                    }
                }
            }
        }

        return $data;
	}
}