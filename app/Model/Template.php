<?php

class Template extends AppModel
{
    /**
    * Name of our Model, table will look like 'adaptcms_templates'
    */
	public $name = 'Template';

	/**
	* Validation rules
	*/
    public $validate = array(
    	'location' => array(
			array(
				'rule' => 'isUnique',
				'message' => 'Template already exists in this location'
			)
        )
    );
    
    /**
    * All templates belong to a theme
    */
	public $belongsTo = array(
		'Theme' => array(
			'className' => 'Theme',
			'foreignKey' => 'theme_id'
		)
	);

	/**
	* Folders to ignore when fetching locations
	*/
	public $ignoreFolders = array(
		'.',
		'..',
		'Themed',
		'Old_Themed',
		'Helper'
	);

    /**
    * Creates folders need for templates
    *
    * @return true
    */
    public function beforeSave()
    {
        if (!empty($this->data) && 
            empty($this->data['Template']['id']) && 
            !empty($this->data['Template']['title']))
        {
			$theme = $this->Theme->find('first', array(
                'conditions' => array(
                	'Theme.id' => $this->data['Template']['theme_id'],
                	'Theme.deleted_time' => '0000-00-00 00:00:00'
                ),
                'fields' => array(
                	'title'
                )
        	));

        	if (strstr($this->data['Template']['location'], 'Plugin/'))
        	{
        		$path = APP;
        	} else {
        		$path = VIEW_PATH;
        	}

        	if (!strstr($this->data['Template']['location'], '.ctp'))
        	{
        		$file = $this->data['Template']['title'];
        		$this->data['Template']['location'] = $this->data['Template']['location'] . '/' . $file;
        	}

        	if (empty($this->data['Template']['nowrite']))
        	{
	        	$fh = fopen($path . $this->data['Template']['location'], 'w');
	        	if ($fh)
	        	{
					fwrite($fh, $this->data['Template']['template']);
					fclose($fh);
				}
			}
        } elseif (!empty($this->data) && !empty($this->data['Template']['old_title']))
        {
			$theme = $this->Theme->find('first', array(
                'conditions' => array(
                	'Theme.id' => $this->data['Template']['theme_id'],
                	'Theme.deleted_time' => '0000-00-00 00:00:00'
                ),
                'fields' => array(
                	'title'
                )
        	));

			$file = str_replace(
				"_".strtolower(basename($this->data['Template']['location'])), 
				"",
				$this->data['Template']['title']
			);

        	if (strstr($this->data['Template']['location'], 'Plugin/'))
        	{
        		$path = APP;
        		$pre = '';
        	} else {
        		$path = VIEW_PATH;
        		$pre = '';
        	}

        	$this->data['Template']['location'] = 
        		$pre.$this->data['Template']['location'] . '/' . $file;

        	if (empty($this->data['Template']['nowrite']))
        	{
	        	$fh = fopen($path . $this->data['Template']['location'], 'w');
				if ($fh)
				{
					fwrite($fh, $this->data['Template']['template']);
					fclose($fh);
				}
			}

			if ($this->data['Template']['location'] != $this->data['Template']['old_location']
				or $this->data['Template']['title'] != $this->data['Template']['old_title']
				or $this->data['Template']['theme_id'] != $this->data['Template']['old_theme'])
			{
				if (is_readable($path . $this->data['Template']['old_location']))
				{
					unlink($path . $this->data['Template']['old_location']);
				}
			}
        }

        return true;
    }

	/**
	* Returns a full folder list when adding/editing a template (setting a location for it)
	*
	* @return array of folders
	*/
	public function folderList()
	{

		$dir = ROOT . '/app/View/';
	    if ($dh = opendir($dir))
	    {
	        while (($file = readdir($dh)) !== false)
	        {
	        	if (!in_array($file, $this->ignoreFolders))
	        	{
	            	$folders[$file] = $file;
	        	}
	        }
	        closedir($dh);
	    }
	    asort($folders);
	    
	    return $folders;
	}

	/**
	* Returns a full folder list when adding/editing a template (setting a location for it)
	*
	* @return array of folders
	*/
	public function getFolders($dir, $inc_dir, $plugin = false)
	{
		$folders = array();

		if ($plugin)
		{
			$ex = explode('/', $inc_dir);
			$prefix = 'Plugin -> ' . $ex[1] . ' -> ';
		} else {
			$prefix = '';
		}

	    if ($dh = opendir($dir))
	    {
	        while (($file = readdir($dh)) !== false)
	        {
	        	if (!in_array($file, $this->ignoreFolders))
	        	{
	            	$folders[$inc_dir.$file] = $prefix . $file;

        			if (!is_file($dir.$file) && $fol = opendir($dir.$file))
        			{
	        			while(($row = readdir($fol)) != false)
	        			{
	        				if ($row != ".." && $row != "." && !is_file($dir.$file.'/' . $row))
	        				{
	        					$folders[$inc_dir.$file.'/' . $row] = $prefix . $file.' -> '.ucfirst($row);

			        			if (!is_file($dir.$file.'/' . $row) && $fol2 = opendir($dir.$file.'/' . $row))
			        			{
				        			while(($val = readdir($fol2)) != false) {
				        				if ($val != ".." && $val != "." && !is_file($dir.$file.'/' . $row.'/' . $val))
				        				{
				        					$folders[$inc_dir.$file.'/' . $row.'/' . $val] = 
				        						$prefix . $file.' -> '.ucfirst($row).' -> '.ucfirst($val);
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

	    return $folders;
	}

	/**
	* Same as folderList(), but looks for theme folders
	*
	* @param folder
	* @return array of folders
	*/
	public function folderFullList($folder = null)
	{
		if ($folder)
		{
			$dir = ROOT . '/app/View/Themed/' . $folder . '/';
			$inc_dir = "Themed/" . $folder . "/";
		} else {
			$dir = ROOT . '/app/View/';
			$inc_dir = null;
		}
		$plugin_dir = APP . DS . 'Plugin' . DS;

		$folders = $this->getFolders($dir, $inc_dir);

     	foreach(Configure::read('Plugins.list') as $plugin)
     	{
     		$view_folder = $plugin_dir . $plugin . DS . 'View' . DS;

     		if ($getFolders = $this->getFolders($view_folder, 'Plugin' . DS . $plugin . DS . 'View' . DS, true))
     		{
     			$folders = array_merge($folders, $getFolders);
     		}
     	}
	    asort($folders);
	    
	    return $folders;		
	}

	/**
	* Same as folderList(), but looks for theme folders and returns list of files
	*
	* @param folder
        * @param plugin false, otherwise name of plugin for plugin assets 
	* @return array of folders and files
	*/
	public function getFolderAndFilesList($dir, $plugin = false)
	{
		$files = array();

		if (!empty($plugin))
		{
			$prefix = 'Plugin/' . $plugin . '/View/';
			$inc_dir = '';
		} else {
			$prefix = '';
			$inc_dir = '';
		}

		if (file_exists($dir))
		{
		    if ($dh = opendir($dir))
		    {
		        while (($file = readdir($dh)) !== false)
		        {
		        	if (!in_array($file, $this->ignoreFolders) && !is_file($file))
		        	{
	        			if ($fol = opendir($dir.$file))
	        			{
		        			while(($row = readdir($fol)) != false)
		        			{
		        				if ($row != ".." && $row != ".")
		        				{
		        					if (is_file($dir.$file."/".$row))
		        					{
		        						$files[$prefix . $inc_dir.$file."/".$row] = $prefix . $inc_dir.$file."/".$row;
		        					} else {
		        						if ($fol2 = opendir($dir.$file."/".$row))
		        						{
		        							while(($row2 = readdir($fol2)) != false)
		        							{
		        								if ($row2 != ".." && $row2 != ".")
		        								{
		        									$files[$prefix . $inc_dir.$file."/".$row."/".$row2] = $prefix . $inc_dir.$file."/".$row."/".$row2;
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
		        closedir($dh);
		    }
	    }

	    return $files;
	}

	/**
	* Same as folderList(), but looks for theme folders and returns list of files
	*
	* @param folder
	* @return array of folders and files
	*/
	public function folderAndFilesList($folder = null)
	{
		if ($folder) {
			$dir = ROOT . '/app/View/Themed/' . $folder . '/';
			$inc_dir = "Themed/".$folder."/";
		} else {
			$dir = ROOT . '/app/View/';
			$inc_dir = null;
		}
		$plugin_dir = APP . DS . 'Plugin' . DS;

		$files = $this->getFolderAndFilesList($dir);

     	foreach(Configure::read('Plugins.list') as $plugin)
     	{
     		$view_folder = $plugin_dir . $plugin . DS . 'View' . DS;

     		if ($getFiles = $this->getFolderAndFilesList($view_folder, $plugin))
     		{
     			$files = array_merge($files, $getFiles);
     		}
     	}

	    if (!empty($files))
	    {
	    	asort($files);
		}

		return $files;
	}
}