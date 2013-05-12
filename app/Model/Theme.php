<?php

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
    * @return true
    */
    public function beforeSave()
    {
        if (!empty($this->data) && 
            empty($this->data['Theme']['id']) && 
            !empty($this->data['Theme']['title']))
        {
            $this->data['Theme']['title'] = $this->slug($this->data['Theme']['title']);
            $this->data['Theme']['title'] = $this->camelCase($this->data['Theme']['title']);

            mkdir(VIEW_PATH . 'Themed/' . $this->data['Theme']['title']);

            mkdir(WWW_ROOT . 'themes/' . $this->data['Theme']['title']);
            mkdir(WWW_ROOT . 'themes/' . $this->data['Theme']['title'] . '/css');
            mkdir(WWW_ROOT . 'themes/' . $this->data['Theme']['title'] . '/js');
            mkdir(WWW_ROOT . 'themes/' . $this->data['Theme']['title'] . '/img');
            
            foreach($this->Template->folderList() as $folder)
            {
                mkdir(VIEW_PATH . 'Themed/' . $this->data['Theme']['title'] . '/' . $folder);
            }
        } elseif (!empty($this->data) && !empty($this->data['Theme']['old_title']))
        {
            $this->data['Theme']['title'] = $this->slug($this->data['Theme']['title']);
            $this->data['Theme']['title'] = $this->camelCase($this->data['Theme']['title']);

            if ($this->data['Theme']['title'] != $this->data['Theme']['old_title'])
            {
                if (file_exists(VIEW_PATH . 'Themed/' . $this->data['Theme']['old_title']))
                {
                    rename(
                        VIEW_PATH . 'Themed/' . $this->data['Theme']['old_title'],
                        VIEW_PATH . 'Themed/' . $this->data['Theme']['title']
                    );
                    rename(
                        WWW_ROOT.'themes/' . $this->data['Theme']['old_title'],
                        WWW_ROOT.'themes/' . $this->data['Theme']['title']
                    );
                } else {
                    return false;
                }
            }
        }

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
    * @param theme name
    * @param plugin false by default, otherwise provides name of plugin
    * @return array of theme assets
    */
    public function getAssets($theme = null, $plugin = false)
    {
        $assets = $this->assetsList($theme, $plugin);
        
        foreach(Configure::read('Plugins.list') as $plugin)
        {
            $assets = array_merge($assets, $this->assets($theme, $plugin));
        }

        return $assets;
    }

    /**
    * Returns list of assets for specified theme
    *
    * @param theme name
    * @param plugin false by default, otherwise provides name of plugin
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
            $path = WWW_ROOT.'themes/' . $theme.'/';
            $view_path = DS . 'themes/' . $theme.'/';
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
                                    $new_path = str_replace('.', '&', $row);

                                    $data[$rel_path . $row] = $row;
                                }
                            }
                        }
                    } else {
                        $new_path = str_replace('.', '&', $file);

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
}