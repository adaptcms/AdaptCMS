<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class AutoLoadJSHelper extends AppHelper
{
	public $helpers = array(
		'Javascript',
		'Html'
	);

	public function afterRenderFile($viewFile, $content)
	{
		$var = '<div id="webroot" style="display:none">'. $this->params->webroot . '</div></body>';

		return str_replace('</body>', $var, $content);
	}

	public function getFiles($ext)
	{
		$controller = strtolower($this->params->controller);
		$action = str_replace("admin_", "", strtolower($this->params->action));

		if (!empty($this->params->prefix)) {
			$controller = $this->params->prefix . '.' . $controller;
		}

		$files = array(
			'controller' => array(
				'path' => WWW_ROOT . $ext . DS,
				'file' => $controller . '.' . $ext,
				'web_file' => $controller . '.' . $ext,
				'ext' => $ext
			),
			'action' => array(
				'path' => WWW_ROOT . $ext . DS,
				'file' => $controller . '.' . $action . '.' . $ext,
				'web_file' => $controller . '.' . $action . '.' . $ext,
				'ext' => $ext
			)
		);

		if (!empty($this->params['plugin']))
		{
			$plugin = ucfirst($this->params['plugin']);

			$files['controller']['path'] = APP . 'Plugin' . DS . $plugin . DS . 'webroot' . DS . $ext . DS;
			$files['action']['path'] = $files['controller']['path'];

			$files['controller']['web_file'] = DS . $plugin . DS . $ext . DS . $files['controller']['web_file'];
			$files['action']['web_file'] = DS . $plugin . DS . $ext . DS . $files['action']['web_file'];
		}

		foreach($files as $file) {
			if (file_exists($file['path'] . $file['file'])) {
				if ($ext == 'js') {
					echo $this->Html->script($file['web_file']);
				} elseif ($ext == 'css') {
					echo $this->Html->css($file['web_file']);
				}
			}
		}

		$path = APP . DS . 'Plugin';
		$plugins = new Folder($path);
		$plugin_list = $plugins->read(true);

		$exclude_list = array(
			'SupportTicket',
			'Facebook'
		);
		
		foreach($plugin_list[0] as $folder) {
			if (!in_array($folder, $exclude_list)) {
				$file_path = $path.DS.$folder.DS.'webroot'.DS.$ext.DS.'global.'.$ext;

				if (file_exists($file_path)) {
					echo $this->Html->script($folder.'.global.'.$ext);
				}
			}
		}
	}

	public function getJS()
	{
		$this->getFiles('js');
	}

	public function getCss()
	{
		$this->getFiles('css');
	}
}