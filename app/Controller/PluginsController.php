<?php
App::uses('AppController', 'Controller');

/**
 * Class PluginsController
 */
class PluginsController extends AppController
{
    /**
    * Name of the Controller, 'Plugins'
    */
	public $name = 'Plugins';

	/**
	* API Component is used to connect to the adaptcms.com website
	*/
	public $components = array(
		'CmsApi'
	);

	/**
	* The Index gets all active and inactive plugins along with basic info.
	* A lookup is performed to get info from the AdaptCMS website.
	*
	* @return array of plugin data
	*/
	public function admin_index()
	{
        $plugins = array();
        $api_lookup = array();

		$active_plugins = $this->getPlugins( $this->Plugin->getActivePath() );
		$inactive_plugins = $this->getPlugins( $this->Plugin->getInactivePath() );

        if (!empty($active_plugins['plugins']) || !empty($inactive_plugins['plugins']))
		    $plugins = array_merge($active_plugins['plugins'], $inactive_plugins['plugins']);

        if (!empty($active_plugins['api_lookup']) || !empty($inactive_plugins['api_lookup']))
		    $api_lookup = array_merge($active_plugins['api_lookup'], $inactive_plugins['api_lookup']);

		if (!empty($api_lookup))
        {
			if ($data = $this->CmsApi->pluginsLookup($api_lookup))
            {
				foreach($plugins as $key => $plugin)
                {
					if (!empty($plugin['api_id']) && !empty($data['data'][$plugin['api_id']]))
                    {
						$plugins[$key]['data'] = $data['data'][$plugin['api_id']];
					}
				}
			}
		}

		$this->set(compact('plugins'));
	}

	/**
	* Before POST, just returns the plugins config params and plugin info.
	*
	* After POST, attempts to update settings from form by updating the plugins config file
	* and sets flash message on success or error.
	*
	* @param string $plugin
	* @return mixed
	*/
	public function admin_settings($plugin)
	{
        $path = $this->Plugin->getActivePath() . $plugin . DS;
		$config_path = $path . 'Config' . DS . 'config.php';

		if (file_exists($config_path))
		{
			$params = Configure::read($plugin);

			if (isset($params['admin_menu']))
				unset($params['admin_menu']);

            if (isset($params['admin_menu_label']))
				unset($params['admin_menu_label']);
		} else {
			$params = array();
		}

        if (is_writable($config_path))
        {
            $this->set('writable', 1);
        }
        else
        {
            $this->set('writable', $config_path);
        }

		if (!empty($this->request->data))
		{
			$orig_contents = file_get_contents($config_path);
			$contents = $this->request->data['Settings'];		

			$new_contents = str_replace( json_encode($params), json_encode($contents), $orig_contents );

        	$fh = fopen($config_path, 'w') or die("can't open file");

        	if (fwrite($fh, $new_contents))
        	{
        		if ($plugin_json = $this->getPluginJson($path . 'plugin.json'))
        		{
        			if (!empty($plugin_json['install']['model_title']))
        			{
        				$model = $plugin_json['install']['model_title'];

        				$this->loadModel($plugin . '.' . $model);

        				if (method_exists($this->$model, 'onSettingsUpdate'))
        				{
        					$this->$model->onSettingsUpdate($params, $contents);
        				}
        			}
        		}

        		$this->Session->setFlash('The Plugin ' . $plugin . ' settings have been updated.', 'flash_success');
        		$params = $contents;
        	} else {
        		$this->Session->setFlash('The Plugin ' . $plugin . ' settings could not be updated.', 'flash_error');
        	}

        	fclose($fh);
		}

		$this->set(compact('plugin', 'params'));
	}
        
        
    /**
     * A simple function that grabs all permissions (grouped by role) for a plugin for editing on page.
     * Flash message on success/error.
     *
     * @param string $plugin
     */
    public function admin_permissions($plugin)
    {
        $this->loadModel('Role');

        $roles = $this->Role->find('all', array(
            'contain' => array(
                'Permission' => array(
                    'conditions' => array(
                        'Permission.plugin' => Inflector::underscore($plugin)
                    ),
                    'order' => 'Permission.controller ASC, Permission.action ASC'
                )
            )
        ));

        $this->set(compact('plugin', 'roles'));

        if (!empty($this->request->data))
        {
            if ($this->Role->Permission->saveMany($this->request->data))
            {
                $this->Session->setFlash('Plugin permissions have been updated.', 'flash_success');
            }
            else
            {
                $this->Session->setFlash('Unable to update plugin permissions.', 'flash_error');
            }
        }
    }

    /**
     * Function hooks into Themes to manage web assets for plugins
     *
     * @param string $plugin
     * @return void
     */
	public function admin_assets($plugin)
	{
        $this->loadModel('Theme');

        $this->set('assets_list', $this->Theme->assetsList(null, $plugin));
        $this->set('assets_list_path', APP);
        $this->set('webroot_path', $this->webroot);

        $this->set(compact('plugin'));
	}

    /**
     * Convienence method, need to get plugin JSON file contents several times in this controller.
     *
     * @param string $path
     * @internal param \of $path plugin JSON file
     * @return array|boolean of data, false if it can't get file contents
     */
	private function getPluginJson($path)
	{
		if (file_exists($path) && is_readable($path))
		{
			$handle = fopen($path, "r");
			$file = fread($handle, filesize($path));

			return json_decode($file, true);
		}

		return false;
	}

	/**
	* Convienence method
	* Goes through folder of Plugins, setting all data needed on plugin listing.
	*
	* @param string $path of specified Plugin folder
	* @return array plugin data with api information
	*/
	private function getPlugins($path)
	{
		$exclude = array(
			'DebugKit',
            'empty'
		);
		$plugins = array();
		$api_lookup = array();

		if ($dh = opendir($path))
        {
			while (($file = readdir($dh)) !== false)
            {
                if (!in_array($file, $exclude) && $file != ".." && $file != ".")
                {
                	$json = $path . DS . $file . DS . 'plugin.json';

		 			if ($plugin = $this->getPluginJson($json))
		 			{
		 				$plugins[$file] = $plugin;

		 				if (!empty($plugins[$file]['api_id'])) {
		 					$api_lookup[] = $plugins[$file]['api_id'];
						}
		 			} else {
		 				$plugins[$file]['title'] = $file;
		 			}

		 			$upgrade = $path . DS . $file . DS . 'Install' . DS . 'upgrade.json';

		 			if (file_exists($upgrade) && is_readable($upgrade))
		 			{
		 				$plugins[$file]['upgrade_status'] = 1;
		 			} else {
		 				$plugins[$file]['upgrade_status'] = 0;
		 			}

		 			if (strstr($path, 'Old')) {
		 				$plugins[$file]['status'] = 0;
		 			} else {
		 				$plugins[$file]['status'] = 1;
		 			}

		 			$config = $path . DS . $file . DS . 'Config' . DS . 'config.php';

		 			if (file_exists($config) && is_readable($config))
		 			{
                        $params = array();

                        require($config);

                        $param_check = json_decode($params);

                        if (!empty($param_check))
		 				    $plugins[$file]['config'] = 1;
		 			}

                    if (!isset($plugins[$file]['config']))
                        $plugins[$file]['config'] = 0;
                }
            }
		}

		return array(
			'plugins' => $plugins,
			'api_lookup' => $api_lookup
		);
	}
}