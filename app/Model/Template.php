<?php

class Template extends AppModel{
	public $name = 'Template';

    public $validate = array(
    	'location' => array(
			array(
				'rule' => 'isUnique',
				'message' => 'Template already exists in this location'
			)
        )
    );
    
	public $belongsTo = array(
		'Theme' => array(
			'className' => 'Theme',
			'foreignKey' => 'theme_id'
			)
	);
	
	public $hasMany = array('Module');

	public $recursive = -1;

	public function folderList()
	{
		$dir = ROOT . '/app/View/';
	    if ($dh = opendir($dir)) {
	        while (($file = readdir($dh)) !== false) {
	        	if ($file != ".." && $file != "." && $file != "Themed" && $file != "Helper") {
	            	$folders[$file] = $file;
	        	}
	        }
	        closedir($dh);
	    }
	    asort($folders);
	    
	    return $folders;
	}

	public function folderFullList($folder = null)
	{
		if ($folder) {
			$dir = ROOT . '/app/View/Themed/' . $folder . '/';
			$inc_dir = "Themed/" . $folder . "/";
		} else {
			$dir = ROOT . '/app/View/';
			$inc_dir = null;
		}

	    if ($dh = opendir($dir)) {
	        while (($file = readdir($dh)) !== false) {
	        	if ($file != ".." && $file != "." && $file != "Themed" && $file != "Helper") {
	            	$folders[$inc_dir.$file] = $file;

        			if (!is_file($dir.$file) && $fol = opendir($dir.$file)) {
	        			while(($row = readdir($fol)) != false) {
	        				if ($row != ".." && $row != "." && !is_file($dir.$file.'/'.$row)) {
	        					$folders[$inc_dir.$file.'/'.$row] = $file.' -> '.ucfirst($row);

			        			if (!is_file($dir.$file.'/'.$row) && $fol2 = opendir($dir.$file.'/'.$row)) {
				        			while(($val = readdir($fol2)) != false) {
				        				if ($val != ".." && $val != "." && !is_file($dir.$file.'/'.$row.'/'.$val)) {
				        					$folders[$inc_dir.$file.'/'.$row.'/'.$val] = 
				        						$file.' -> '.ucfirst($row).' -> '.ucfirst($val);
				        				}
				        			}
				        		}
				        		closedir($fol2);
	        				}
	        			}
	        		}
	        		closedir($fol);
	        	}
	        }
	        closedir($dh);
	    }
	    asort($folders);
	    
	    return $folders;		
	}

	public function folderAndFilesList($folder = null)
	{
		if ($folder) {
			$dir = ROOT . '/app/View/Themed/' . $folder . '/';
			$inc_dir = "Themed/".$folder."/";
		} else {
			$dir = ROOT . '/app/View/';
			$inc_dir = null;
		}
		$files = null;

		if (file_exists($dir)) {
		    if ($dh = opendir($dir)) {
		        while (($file = readdir($dh)) !== false) {
		        	if ($file != ".." && $file != "." && $file != "Themed" && $file != "Helper") {
		        		if (!is_file($file)) {
		        			if ($fol = opendir($dir.$file)) {
			        			while(($row = readdir($fol)) != false) {
			        				if ($row != ".." && $row != ".") {
			        					if (is_file($dir.$file."/".$row)) {
			        						$files[$inc_dir.$file."/".$row] = $inc_dir.$file."/".$row;
			        					} else {
			        						if ($fol2 = opendir($dir.$file."/".$row)) {
			        							while(($row2 = readdir($fol2)) != false) {
			        								if ($row2 != ".." && $row2 != ".") {
			        									$files[$inc_dir.$file."/".$row."/".$row2] = $inc_dir.$file."/".$row."/".$row2;
			        								}
			        							}
			        						}
			        						closedir($fol2);
			        					}
			        				}
			        			}
			        		}
		        			closedir($fol);
		        		}
		        	}
		        }
		        closedir($dh);
		    }
		    if (!empty($files)) {
		    	asort($files);
			}

		    return $files;
		}
	}
}