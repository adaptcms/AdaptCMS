<?php
/**
 * Class Template
 *
 * @property Theme $Theme
 */
class Template extends AppModel
{
    /**
    * Name of our Model, table will look like 'adaptcms_templates'
    */
	public $name = 'Template';

	/**
     * TODO: Need a unique location rule, but checks by theme_id as well
	* Validation rules
	*/
    public $validate = array(
//    	'location' => array(
//			array(
//				'rule' => 'isUnique',
//				'message' => 'Template already exists in this location'
//			)
//        )
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
	 * @var array
	 */
	public $actsAs = array('Delete');

	/**
	* Folders to ignore when fetching locations
	*/
	public $ignoreFolders = array(
		'.',
		'..',
		'Themed',
		'Old_Themed',
		'Helper',
        'Install',
        'theme.json',
        'plugin.json',
        'empty',
		'AdaptcmsView.php',
		'webroot',
		'readme.md',
		'LICENSE.txt',
		'LICENSE',
		'README',
		'README.md'
	);

    /**
     * Files to ignore
     *
     * @var array
     */
    public $ignoreFiles = array(
        '.',
        '..',
        'empty'
    );
	
	public $template_extension = 'ctp';

    /**
    * Creates folders need for templates
    *
    * @param array $options
    *
    * @return boolean
    */
    public function beforeSave($options = array())
    {
	    if (!empty($this->data['Template']['title']) && !strstr($this->data['Template']['title'], '.ctp'))
		    $this->data['Template']['title'] = $this->data['Template']['title'] . '.ctp';

        if (!empty($this->data) && 
            empty($this->data['Template']['id']) && 
            !empty($this->data['Template']['title']))
        {
			$theme = $this->Theme->find('first', array(
                'conditions' => array(
                	'Theme.id' => $this->data['Template']['theme_id']
                ),
                'fields' => array(
                	'title'
                )
        	));

        	if (strstr($this->data['Template']['location'], 'Plugin/'))
        	{
        		$path = APP;
            } elseif ($this->data['Template']['theme_id'] != 1) {
                $path = VIEW_PATH . 'Themed' . DS . $theme['Theme']['title'] . DS;
        	} else {
        		$path = VIEW_PATH;
        	}

        	if (!strstr($this->data['Template']['location'], $this->template_extension))
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

                chmod($path . $this->data['Template']['location'], 0777);
			}
        } elseif (!empty($this->data) && !empty($this->data['Template']['old_title']))
        {
			$theme = $this->Theme->find('first', array(
                'conditions' => array(
                	'Theme.id' => $this->data['Template']['theme_id']
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
            } elseif ($this->data['Template']['theme_id'] != 1) {
                $path = VIEW_PATH . 'Themed' . DS . $theme['Theme']['title'] . DS;
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
					unlink($path . $this->data['Template']['old_location']);

                chmod($path . $this->data['Template']['location'], 0777);
			}
        }

	    if (empty($this->data['Template']['label']) && !empty($this->data['Template']['title']))
		    $this->data['Template']['label'] = str_replace('.' . $this->template_extension, '', $this->data['Template']['title']);

        return true;
    }

	/**
	 * Before Delete
	 *
	 * @param bool $cascade
	 * @return bool
	 */
	public function beforeDelete($cascade = true)
	{
		$data = $this->findById($this->id);

		if (strstr($data['Template']['location'], 'Plugin'))
		{
			$path = APP;
		} elseif ($data['Template']['theme_id'] != 1) {
			$theme = $this->Theme->findById($data['Template']['theme_id']);
			$path = VIEW_PATH . 'Themed' . DS . $theme['Theme']['title'] . DS;
		} else {
			$path = VIEW_PATH;
		}

		if (is_readable($path . $data['Template']['location']))
		{
			unlink($path . $data['Template']['location']);
		}

		return true;
	}

	/**
	 * Returns a full folder list when adding/editing a template (setting a location for it)
	 *
	 * @param null $dir
	 * @return array of folders
	 */
	public function folderList($dir = null)
	{
		if (empty($dir))
			$dir = VIEW_PATH;

		$folders = array();
		if ($dh = opendir($dir))
	    {
	        while (($file = readdir($dh)) !== false)
	        {
	        	if (!in_array($file, $this->ignoreFolders) && !strstr($file, '.ctp'))
	        	{
	            	$folders[$file] = $file;
	        	}
	        }
	        closedir($dh);
	    }

		if (!empty($folders))
	        asort($folders);
	    
	    return $folders;
	}

	/**
	 * Returns a full folder list when adding/editing a template (setting a location for it)
	 *
	 * @param $dir
	 * @param $inc_dir
	 * @param bool $plugin
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
	        	if (!in_array($file, $this->ignoreFolders) && !is_file($dir . $file))
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
                                    closedir($fol2);
				        		}
	        				}
	        			}
                        closedir($fol);
	        		}
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
//			$inc_dir = "Themed/" . $folder . "/";
		    $inc_dir = null;
        } else {
			$dir = ROOT . '/app/View/';
			$inc_dir = null;
		}
		$plugin_dir = APP . DS . 'Plugin' . DS;

		$folders = $this->getFolders($dir, $inc_dir);

        if (empty($folder))
        {
            foreach(Configure::read('Plugins.list') as $plugin)
            {
                $view_folder = $plugin_dir . $plugin . DS . 'View' . DS;

                if ($getFolders = $this->getFolders($view_folder, 'Plugin' . DS . $plugin . DS . 'View' . DS, true))
                {
                    $folders = array_merge($folders, $getFolders);
                }
            }
        }

	    asort($folders);
	    
	    return $folders;		
	}

	/**
	* Same as folderList(), but looks for theme folders and returns list of files
	*
	* @param string $dir folder
    * @param boolean $plugin false, otherwise name of plugin for plugin assets
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
            $dh = opendir($dir);
		    if ($dh)
		    {
		        while (($file = readdir($dh)) !== false)
		        {
		        	if (!in_array($file, $this->ignoreFolders) && !is_file($file))
		        	{
                        $fol = opendir($dir . $file);
	        			if ($fol)
	        			{
		        			while(($row = readdir($fol)) != false)
		        			{
		        				if (!in_array($row, $this->ignoreFiles))
		        				{
		        					if (is_file($dir . $file . DS . $row))
		        					{
		        						$files[$prefix . $inc_dir . $file . DS . $row] = $prefix . $inc_dir . $file . DS . $row;
		        					}
                                    else
                                    {
                                        $fol2 = opendir($dir . $file . DS . $row);
		        						if ($fol2)
		        						{
		        							while(($row2 = readdir($fol2)) != false)
		        							{
                                                if (!in_array($row2, $this->ignoreFiles))
                                                {
                                                    if (is_file($dir . $file . DS . $row . DS . $row2))
                                                    {
                                                        $files[$prefix . $inc_dir . $file . DS . $row . DS . $row2] = $prefix . $inc_dir.$file.DS.$row.DS.$row2;
                                                    }
                                                    else
                                                    {
                                                        $fol3 = opendir($dir. $file. DS . $row . DS . $row2);
                                                        if ($fol3)
                                                        {
                                                            while(($row3 = readdir($fol3)) != false)
                                                            {
                                                                if (!in_array($row3, $this->ignoreFiles) && is_file($dir . $file . DS . $row . DS . $row2 . DS . $row3))
                                                                {
                                                                    $files[$prefix . $inc_dir . $file . DS . $row . DS . $row2 . DS . $row3] = $prefix . $inc_dir . $file . DS . $row . DS . $row2 . DS . $row3;
                                                                }
                                                            }
                                                        }
                                                    }
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
//			$inc_dir = "Themed/".$folder."/";
		} else {
			$dir = ROOT . '/app/View/';
//			$inc_dir = null;
		}
		$plugin_dir = APP . DS . 'Plugin' . DS;

		$files = $this->getFolderAndFilesList($dir);

        if (empty($folder))
        {
            foreach(Configure::read('Plugins.list') as $plugin)
            {
                $view_folder = $plugin_dir . $plugin . DS . 'View' . DS;

                if ($getFiles = $this->getFolderAndFilesList($view_folder, $plugin))
                {
                    $files = array_merge($files, $getFiles);
                }
            }
        }

	    if (!empty($files))
	    {
	    	asort($files);
		}

		return $files;
	}

	/**
	 * Set Global Vars
	 *
	 * @param $data
	 * @return array
	 */
	public function setGlobalVars($data)
	{
		$new_data = array();
		if (!empty($data)) {
			$tags = array();
			$i = 0;
			foreach($data as $row) {
				if ($row['enabled'] != 'false' && !empty($row['tag']) && !empty($row['value']) && !in_array($row['tag'], $tags)) {
					$tag = $this->slug($row['tag'], true);

					$new_data[$i]['tag'] = '{{ ' . $tag . ' }}';
					$new_data[$i]['value'] = $row['value'];

					$tags[] = $tag;

					$i++;
				}
			}
		}

		return $new_data;
	}

	/**
	 * Get Global Vars
	 *
	 * @return array
	 */
	public function getGlobalVars()
	{
		$data = Configure::read('global_vars');

		if (!empty($data)) {
			$find = array(
				'{{ ',
				' }}'
			);

			foreach($data as $key => $row) {
				$data[$key]['tag'] = str_replace($find, '', $row['tag']);
				$data[$key]['enabled'] = true;
			}
		}

		return $data;
	}

	/**
	 * Update Global Vars
	 *
	 * @param $data
	 * @return string
	 */
	public function updateGlobalVars($data)
	{
		$vars = $this->setGlobalVars($data);

		$path = APP . 'Config' . DS . 'configuration.php';

		$old = Configure::read('global_vars');

		$orig_contents = file_get_contents($path);
		$new_contents = str_replace( "'" . json_encode($old) . "'", "'" . json_encode($vars) . "'", $orig_contents );
		$fh = fopen($path, 'w') or die("can't open file");

		$status = 'error';
		if (fwrite($fh, $new_contents)) {
			$status = 'success';
		}

		return $status;
	}
}