<?php

class PluginsController extends AppController
{
	public $name = 'Plugins';
	public $components = array(
		'Api'
	);
	public $uses = array();

	public function admin_index()
	{
		$active_path = APP . 'Plugin';
		$active_plugins = $this->getPlugins($active_path);

		$inactive_path = APP . 'Old_Plugins';
		$inactive_plugins = $this->getPlugins($inactive_path);

		$plugins = array_merge($inactive_plugins['plugins'], $active_plugins['plugins']);
		$api_lookup = array_merge($inactive_plugins['api_lookup'], $active_plugins['api_lookup']);

		if (!empty($api_lookup)){
			if ($data = $this->Api->pluginsLookup($api_lookup)) {
				foreach($plugins as $key => $plugin) {
					if (!empty($plugin['api_id']) && !empty($data['data'][$plugin['api_id']])) {
						$plugins[$key]['data'] = $data['data'][$plugin['api_id']];
					}
				}
			}
		}

		$this->set(compact('plugins'));
	}

	public function admin_settings($plugin)
	{
		$path = APP . 'Plugin' . DS . $plugin;
		$config_path = $path . DS . 'Config' . DS . 'config.php';

		if (file_exists($config_path))
		{
			require_once $config_path;
		} else {
			$params = array();
		}

		if (!empty($this->request->data))
		{
			$orig_contents = file_get_contents($config_path);
			$contents = json_encode($this->request->data['Settings']);

			$new_contents = str_replace($params, $contents, $orig_contents);

        	$fh = fopen($config_path, 'w') or die("can't open file");

        	if (fwrite($fh, $new_contents))
        	{
        		$this->Session->setFlash('The Plugin ' . $plugin . ' settings have been updated.', 'flash_success');
        		$params = $contents;
        	}

        	fclose($fh);
		}

		if (!empty($params) && is_string($params))
		{
			$params = json_decode($params, true);
		}

		$this->set(compact('plugin', 'params'));
	}

	private function getPlugins($path)
	{
		$exclude = array(
			'DebugKit'
		);
		$plugins = array();
		$api_lookup = array();

		if ($dh = opendir($path)) {
			while (($file = readdir($dh)) !== false) {
                if (!in_array($file, $exclude) && $file != ".." && $file != ".") {
                	$json = $path . DS . $file . DS . 'plugin.json';

					if (file_exists($json) && is_readable($json)) {
		 				$handle = fopen($json, "r");
		 				$json_file = fread($handle, filesize($json));

		 				$plugins[$file] = json_decode($json_file, true);

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
		 				$plugins[$file]['config'] = 1;
		 			} else {
		 				$plugins[$file]['config'] = 0;
		 			}
                }
            }
		}

		return array(
			'plugins' => $plugins,
			'api_lookup' => $api_lookup
		);
	}
}