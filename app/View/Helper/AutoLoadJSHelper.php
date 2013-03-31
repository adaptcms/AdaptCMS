<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class AutoLoadJSHelper extends AppHelper
{
    /**
     * Name of the helper
     * 
     * @var string
     */
    public $name = 'AutoLoadJS';
    
    /**
     * Array of needed helpers
     * 
     * @var array
     */
    public $helpers = array(
        'Javascript',
        'Html'
    );

    /**
     * Injects the location to the main webroot, used primarily in external JS files for AJAX calls.
     * 
     * @param string $viewFile
     * @param string $content
     * @return string
     */
    public function afterRenderFile($viewFile, $content)
    {
        $var = '<div id="webroot" style="display:none">'. $this->params->webroot . '</div></body>';

        return str_replace('</body>', $var, $content);
    }

    /**
     * Function that looks for any CSS or JS files that should be loaded from plugins or based on URL parameters.
     * An example would be when editing an article in the admin, it will look for and load the 'admin.articles.js' file.
     * 
     * Any css or JS with the name 'global' will also be loaded from plugins.
     * 
     * @param string $ext
     * @return none
     */
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
            'DebugKit'
        );

        foreach($plugin_list[0] as $folder) {
            if (!in_array($folder, $exclude_list)) {
                $file_path = $path . DS . $folder . DS . 'webroot' . DS . $ext . DS . 'global.' . $ext;

                if (file_exists($file_path)) {
                        echo $this->Html->script($folder . '.global.' . $ext);
                }
            }
        }
    }

    /**
     * Runs the getFiles function for JS files
     * 
     * @return none
     */
    public function getJS()
    {
        $this->getFiles('js');
    }

    /**
     * Runs the getFiles function for CSS files
     * 
     * @return none
     */
    public function getCss()
    {
        $this->getFiles('css');
    }
}