<?php
/**
 * Class Theme
 *
 * @property Template $Template
 *
 * @method findByTitle(string $title)
 */
class Theme extends AppModel
{
    /**
    * Name of our Model, table will look like 'adaptcms_themes'
    */
	public $name = 'Theme';

    /**
    * A theme has many templates
    */
	public $hasMany = array(
        'Template' => array(
            'dependent' => true
        )
    );

    /**
    * Validation rules
    */
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

	/**
	 * @var array
	 */
	public $actsAs = array(
		'Delete' => array(
			'cascade' => true
		)
	);

    /**
    * Types of files that can be edited
    */
    public $file_types_editable = array(
        'txt',
        'php',
        'html',
        'css',
        'js',
        'phps',
        'htm',
        'less',
        'sass',
        'scss'
    );

    /**
    * Creates folders need for themes
    *
    * @param array $options
    *
    * @return boolean
    */
    public function beforeSave($options = array())
    {
        if (empty($this->data['Theme']['skipBeforeSave']))
        {
            if (!empty($this->data) &&
                empty($this->data['Theme']['id']) &&
                !empty($this->data['Theme']['title']))
            {
                $this->data['Theme']['title'] = $this->camelCase($this->data['Theme']['title']);
	            $path = VIEW_PATH . 'Themed/' . $this->data['Theme']['title'];

                if (!file_exists($path))
                    mkdir($path);

                if (!file_exists($path . '/webroot'))
                    mkdir($path . '/webroot');

                if (!file_exists($path . '/webroot/css'))
                    mkdir($path . '/webroot/css');

                if (!file_exists($path . '/webroot/js'))
                    mkdir($path . '/webroot/js');

                if (!file_exists($path . '/webroot/img'))
                    mkdir($path . '/webroot/img');

                foreach($this->Template->folderList() as $folder)
                {
                    if (!file_exists($path . '/' . $folder))
                        mkdir($path . '/' . $folder);

					$folders = $this->Template->folderList(VIEW_PATH . $folder);
	                if (!empty($folders)) {
		                foreach($folders as $sub) {
			                if (!file_exists($path . '/' . $folder . DS . $sub))
				                mkdir($path . '/' . $folder . DS . $sub);

			                $list = $this->Template->folderList(VIEW_PATH . $folder . DS . $sub);

			                if (!empty($list)) {
				                foreach($list as $lvl3) {
					                if (!file_exists($path . '/' . $folder . DS . $sub . DS . $lvl3))
						                mkdir($path . '/' . $folder . DS . $sub . DS . $lvl3);
				                }
			                }
		                }
	                }
                }
            } elseif (!empty($this->data) && !empty($this->data['Theme']['old_title']))
            {
                $this->data['Theme']['title'] = $this->camelCase($this->data['Theme']['title']);

                if ($this->data['Theme']['title'] != $this->data['Theme']['old_title'])
                {
                    if (file_exists(VIEW_PATH . 'Themed/' . $this->data['Theme']['old_title']))
                    {
                        rename(
                            VIEW_PATH . 'Themed/' . $this->data['Theme']['old_title'],
                            VIEW_PATH . 'Themed/' . $this->data['Theme']['title']
                        );
                    } else {
                        return false;
                    }
                }
            }
        }

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
		$theme = $this->findById($this->id);

		if (file_exists(VIEW_PATH . 'Themed/' . $theme['Theme']['title']))
			$this->recursiveDelete(VIEW_PATH . 'Themed/' . $theme['Theme']['title']);

		return true;
	}

    /**
    * Transforms a text string into camel cased text, used in controllers
    *
    * @param string $string to camel case the text
    * @param string $spaces if set, adds spaces for camel case words
    * @return string
    */
	public function camelCase($string, $spaces = null) 
	{ 
	  	$string = ucwords(str_replace(array('-', '_'), ' ', $string));

	  	if (!$spaces) {
	  		return str_replace(' ', '', $string);
	  	} else {
	  		return $string;
		}
	}

    /**
    * Returns list of assets for specified theme along with plugin assets
    *
    * @param string $theme theme name
    * @param boolean $plugin false by default, otherwise provides name of plugin
    * @return array of theme assets
    */
    public function getAssets($theme = null, $plugin = false)
    {
        $assets = $this->assetsList($theme, $plugin);
        
        foreach(Configure::read('Plugins.list') as $plugin)
        {
            $assets = array_merge($assets, $this->getAssets($theme, $plugin));
        }

        return $assets;
    }

    /**
    * Returns list of assets for specified theme
    *
    * @param string $theme theme name
    * @param boolean $plugin false by default, otherwise provides name of plugin
    * @return array of theme assets
    */
	public function assetsList($theme = null, $plugin = false)
	{
        if (empty($theme) || $theme == 'Default')
        {
            $path = WWW_ROOT;
            $rel_path = '/';
            $view_path = '/';
        } else {
            $path = VIEW_PATH.'Themed/' . $theme.'/webroot/';
            $view_path = DS . 'theme' . DS . $theme.'/';
            $rel_path = '/';
        }

        if (!empty($plugin))
        {
            $path = APP . DS . 'Plugin' . DS . $plugin . DS . 'webroot' . DS;
            $rel_path = DS . 'Plugin' . DS . $plugin . DS . 'webroot' . DS;
            $view_path = DS . $plugin . DS;
        }

        $rel_path = urlencode( str_replace('/', '__', $rel_path) );

        $exclude = array('.', 'themes', '.htaccess', 'index.php', 'uploads', 'libraries', 'font', 'installer');
        $exclude2 = array('..', 'fancybox', 'tiny_mce');

        if (!file_exists($path))
        {
            return array();
        }

        $data = array();

        if ($dh = opendir($path))
        {
            while (($file = readdir($dh)) !== false)
            {
                if (!in_array($file, $exclude) && $file != ".." && $file != ".")
                {
                    if (is_dir($path.$file) && $fol = opendir($path.$file))
                    {
                        while(($row = readdir($fol)) != false)
                        {
                            if ($row != ".." && $row !="." && !in_array($row, $exclude2))
                            {
                                if ($file != ".")
                                {
                                    $new_path = str_replace('.', '&', $file . '__' . $row);

                                    $data[$rel_path . $new_path] = $file . DS . $row;
                                } else {
                                    $data[$rel_path . $row] = $row;
                                }
                            }
                        }
                    } else {
                    	$data[$rel_path . $file] = $file;
                    }
                }
            }
        }

        return array(
            'assets' => $data,
            'path' => $path,
            'view_path' => $view_path
        );
	}

	/**
	 * Removes files inside of a folder
	 *
	 * @param string $dir path to folder to loop through
	 * @return null
	 */
	public function rrmdir($dir)
	{
		/**
		 * Source: Anonymous
		 * http://us2.php.net/manual/en/function.rmdir.php#107233
		 **/
		if (is_dir($dir))
		{
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..")
				{
					if (filetype($dir . "/" . $object) == "dir")
					{
						$this->rrmdir($dir . "/" . $object);
					} else {
						unlink($dir . "/" . $object);
					}
				}
			}

			reset($objects);
			rmdir($dir);
		}
	}

	/**
	 * Gets config file for a theme with a JSON file
	 *
	 * @param $name
	 * @param string $folder
	 * @return array Array of configuration, blank array on false
	 */
	public function getConfig($name, $folder = 'Themed')
	{
		$json = VIEW_PATH . DS . $folder . DS . $name . DS . 'theme.json';

		if (file_exists($json) && is_readable($json)) {
			$handle = fopen($json, "r");
			$json_file = fread($handle, filesize($json));

			return json_decode($json_file, true);
		}

		return array();
	}

	/**
	 * The function gets parameters needed on the view such as status of the Theme
	 *
	 * @param string $path path to themes
	 * @return array of theme data
	 */
	public function getThemes($path)
	{
		$themes = array();
		$api_lookup = array();
		$exclude = array('empty');

		if ($dh = opendir($path)) {
			while (($file = readdir($dh)) !== false) {
				if (!in_array($file, $exclude) && $file != ".." && $file != ".") {
					$json = $path . DS . $file . DS . 'theme.json';

					if (file_exists($json) && is_readable($json)) {
						$handle = fopen($json, "r");
						$json_file = fread($handle, filesize($json));

						$themes[$file] = json_decode($json_file, true);

						if (!empty($themes[$file]['api_id'])) {
							$api_lookup[] = $themes[$file]['api_id'];
						}

						$upgrade = $path . DS . $file . DS . 'Install' . DS . 'upgrade.json';

						if (file_exists($upgrade) && is_readable($upgrade))
						{
							$themes[$file]['upgrade_status'] = 1;
						} else {
							$themes[$file]['upgrade_status'] = 0;
						}

						if (strstr($path, 'Old')) {
							$themes[$file]['status'] = 0;
						} else {
							$themes[$file]['status'] = 1;
						}
					}
				}
			}
		}

		return array(
			'themes' => $themes,
			'api_lookup' => $api_lookup
		);
	}

	/**
	 * Get Themes List
	 *
	 * @return array
	 */
	public function getThemesList()
	{
		$themes = $this->find('list', array(
			'order' => 'Theme.id ASC',
		));

		return $themes;
	}

	/**
	 * Get Themes By Name
	 *
	 * @param $themes
	 * @return mixed
	 */
	public function getThemesByName($themes)
	{
		if (!empty($themes)) {
			foreach($themes as $key => $theme) {
				$themes[$theme] = $theme;
				unset($themes[$key]);
			}
		}

		return $themes;
	}

	/**
	 * Refresh Theme
	 *
	 * @param $id
	 * @param null $name
	 * @return array
	 */
	public function refreshTheme($id, $name = null)
	{
		if (!empty($name)) {
			$files = $this->Template->folderAndFilesList($name);
		} else {
			$files = $this->Template->folderAndFilesList();
		}

		try {
			if (!empty($files)) {
				$data = $this->Template->find('all', array(
					'conditions' => array(
						'Template.theme_id' => $id
					),
					'fields' => array(
						'Template.location'
					)
				));

				$key = 0;
				$templates = array();

				$this->Template->create();

				if (!empty($data))
					$data = Set::extract('{n}.Template.location', $data);

				foreach ($files as $file) {
					if (empty($data) || !empty($data) && !in_array($file, $data)) {
						$title = explode('/', $file);

						$templates[$key]['Template']['title'] = end($title);
						$templates[$key]['Template']['label'] =
							str_replace('Plugin View', 'Plugin', str_replace('.ctp', '', Inflector::humanize(str_replace("/", " ", $file))));
						$templates[$key]['Template']['location'] = $file;
						$templates[$key]['Template']['theme_id'] = $id;
						$templates[$key]['Template']['created'] = $this->dateTime();
						$templates[$key]['Template']['nowrite'] = true;

						$key++;
					}
				}

				if (!empty($templates))
					$this->Template->saveAll($templates);
			}

			$type = 'success';
			$message = 'The theme has been refreshed.';
		} catch(Exception $e) {
			$type = 'error';
			$message = 'The Theme could not be refreshed.';
		}

		return array(
			'type' => $type,
			'message' => $message
		);
	}
}