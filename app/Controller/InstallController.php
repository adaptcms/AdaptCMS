<?php
App::import('Model', 'ConnectionManager');
App::uses('Controller', 'Controller');

/**
 * Class InstallController
 */
class InstallController extends Controller
{
    public $name = 'Install';
    public $uses = null;
    public $helpers = array('Form');
    public $components = array('Session');

    private $upgrade_versions = array(
        'beta' => array(
            'sql' => array(
                'beta'
            ),
            'upgrade_text' => 'upgrade-beta.md'
        ),
        'beta2' => array(
            'sql' => array(
                'beta',
                'beta2'
            )
        ),
        'beta3' => array(
            'sql' => array(
                'beta',
                'beta2',
                'beta3'
            )
        )
    );

    public function beforeFilter()
    {
        $this->layout = 'install';
        $this->viewPath = 'View';

        $actions = array(
            'sql',
            'account'
        );

        if (in_array($this->params->action, $actions))
            $this->_tryDbConnection();

        $api_actions = array(
            'install_theme',
            'uninstall_theme',
            'upgrade_theme',
            'install_plugin',
            'uninstall_plugin',
            'upgrade_plugin',
            'upgrade'
        );

        if (in_array($this->params->action, $api_actions))
        {
            if ($this->Session->read('Auth.User.Role.id'))
            {
                $role_id = $this->Session->read('Auth.User.Role.id');
            }
            else
            {
                $role_id = 0;
            }

            $this->loadModel('Permission');
            $permission = $this->Permission->find('first', array(
                'conditions' => array(
                    'Permission.controller' => 'install',
                    'Permission.action' => $this->params->action,
                    'Permission.status' => 1,
                    'Permission.role_id' => $role_id
                )
            ));

            if (empty($permission) || $permission['Permission']['status'] == 0)
                throw new ForbiddenException();
        }
    }

    public function _tryDbConnection()
    {
        try {
            $db = ConnectionManager::getDataSource('default');
            if ($db->isConnected()) {
                if (file_exists($this->_installFile()))
                    $this->redirect('/');
            }
        } catch (Exception $e) {
        }
    }

    public function _installFile()
    {
        return APP . 'tmp' . DS . 'system' . DS .'installed.txt';
    }

    public function _updateInstallFile()
    {
        $file = $this->_installFile();
        if (!file_exists($file))
        {
            $fp = fopen($file, 'w') or die("can't open file");
            fwrite($fp, ADAPTCMS_VERSION);
            fclose($fp);
        }

        return true;
    }

    public function index()
    {
        $this->set('title_for_layout', 'Install AdaptCMS');
        $this->set('upgrade_versions', $this->upgrade_versions);

        $this->Session->setFlash('If you have already installed AdaptCMS, then please proceed to your main page.', 'flash_notice');
    }

    public function database()
    {
        $this->set('title_for_layout', 'Install AdaptCMS :: Database Configuration');

        if (!empty($this->request->data)) {
            $file = APP.'Config/database.php';

            $new_file = file_get_contents($file);

            $match = array('{host}', '{login}', '{password}', '{database}', '{prefix}');
            $replace = array(
                $this->request->data['host'],
                $this->request->data['login'],
                $this->request->data['password'],
                $this->request->data['database'],
                $this->request->data['prefix']
            );

            $new_file = str_replace($match, $replace,  $new_file);

            $fh = fopen($file, 'w') or die("can't open file");
            fwrite($fh, $new_file);
            fclose($fh);

            $file_core = APP.'Config/config.php';

            $core_file = file_get_contents($file_core);

            /*
            * Security Salt / cipherSeed
            */
            $match = array(
                'a668f877ee39dec0ac3c59a91970011538c20c30',
                '353137383463373366616438373331'
                );
            $replace = array(
                $this->generateSecuritySalt(),
                $this->generateCipherSeed()
                );

            $core_file = str_replace($match, $replace, $core_file);

            $fhS = fopen($file_core, 'w') or die("can't open file");
            fwrite($fhS, $core_file);
            fclose($fhS);

            try {
                $db = ConnectionManager::getDataSource('default');
                if ($db->isConnected()) {
                    $this->Session->setFlash('You made a successfull connection to the database.', 'flash_success');
                    $this->redirect(array('action' => 'sql'));
                }
            } catch (Exception $e) {
                copy(APP.'Config/database.default.php', $file);

                $this->Session->setFlash('Cannot make connection with the database options you entered.', 'flash_error');
            }
        }
    }

    public function sql()
    {
        $this->set('title_for_layout', 'Install AdaptCMS :: Setup SQL');

        $prefix = ConnectionManager::enumConnectionObjects();

        if (!empty($this->request->data)) {
            $this->set('sql_results', $this->runSQL($prefix['default']['prefix'], 'latest', 1, 1));
        }
    }

    public function account()
    {
        $this->set('title_for_layout', 'Install AdaptCMS :: Create Admin Account');

        $db = ConnectionManager::getDataSource('default');

        $this->loadModel('SettingValue');
        $this->loadModel('User');

        $this->request->data['SecurityQuestions'] = $this->SettingValue->findByTitle('Security Questions');
        $security_options = $this->User->getSecurityOptions($this->SettingValue->findByTitle('Security Question Options'));
        $user_status = $this->SettingValue->findByTitle('User Status');
        $this->set(compact('security_options'));

        if (!empty($this->request->data) && !empty($this->request->data['Security'])) {
            $this->loadModel('User');

            $this->request->data['User']['security_answers'] = json_encode($this->request->data['Security']);
            $this->request->data['User']['role_id'] = 1;
            $this->request->data['User']['status'] = 1;

            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash('Your admin account was created.', 'flash_success');
                $this->redirect(array('action' => 'finish'));
            } else {
                $this->Session->setFlash('Your admin account could nto be created.', 'flash_error');
            }
        }
    }

    public function finish()
    {
        $this->_updateInstallFile();

        $this->set('title_for_layout', 'Install AdaptCMS :: Finish');
    }

    public function upgrade($version = null)
    {
        $this->set('title_for_layout', 'Install AdaptCMS :: Upgrade');
        $this->set(compact('version'));

        if (!in_array($version, array_keys($this->upgrade_versions) ))
        {
            $this->redirect(array('action' => 'index'));
        }

        $notes_path = WWW_ROOT.'installer'.DS.'notes'.DS;

        if (!empty($this->upgrade_versions[$version]['upgrade_text']) && file_exists($notes_path . $this->upgrade_versions[$version]['upgrade_text']))
        {
            $this->set('upgrade_text', file_get_contents($notes_path . $this->upgrade_versions[$version]['upgrade_text']) );
        }

        if (!empty($this->request->data))
        {
            $prefix = ConnectionManager::enumConnectionObjects();

            if (!empty($this->upgrade_versions[$version]['sql']))
            {
                $sql_results = array();

                foreach($this->upgrade_versions[$version]['sql'] as $file)
                {
                    $results = $this->runSQL($prefix['default']['prefix'], $file . '-upgrade', 1, 0);
                    $sql_results[] = $results;

                    if (!empty($results['error']))
                        $error = 1;
                }

                $this->set( compact('sql_results') );
            }

            if (!isset($error))
                $this->_updateInstallFile();
        }
    }

    public function runSQL($prefix, $version, $data = null, $tables = null)
    {
        $db = ConnectionManager::getDataSource('default');

        $path = WWW_ROOT.'installer'.DS.'sql'.DS;
        $return = array();

        if (!empty($tables)) {
            $tables_count1 = 0;
            $tables_count2 = 0;

            $tables_file = file_get_contents($path.'tables-'.$version.'.sql');
            $ex = explode("-- --------------------------------------------------------", $tables_file);

            foreach($ex as $row) {
                if ( !empty($row) )
                {
                    $matches = array(
                        '{prefix}',
                        '{date}'
                        );
                    $replaces = array(
                        $prefix,
                        date('Y-m-d H:i:s')
                        );
                    $row = str_replace( $matches, $replaces, $row );

                    if ($db->rawQuery($row)) {
                        $tables_count1++;
                    }

                    $tables_count2++;
                }
            }

            $return['tables'] = array(
                $tables_count1,
                $tables_count2
                );
            $return['file'] = 'tables-'.$version.'.sql';

            if ($tables_count1 != $tables_count2)
            {
                $return['error'] = 1;
            }
        }

        if (!empty($data)) {
            $data_count1 = 0;
            $data_count2 = 0;

            $data_file = file_get_contents($path.'data-'.$version.'.sql');
            $ex = explode("-- --------------------------------------------------------", $data_file);

            foreach($ex as $row) {
                if ( !empty($row) )
                {
                    $matches = array(
                        '{prefix}',
                        '{date}'
                        );
                    $replaces = array(
                        $prefix,
                        date('Y-m-d H:i:s')
                        );
                    $row = str_replace( $matches, $replaces, $row );

                    if ($db->rawQuery($row)) {
                        $data_count1++;
                    }

                    $data_count2++;
                }
            }

            $return['data'] = array(
                $data_count1, 
                $data_count2
                );
            $return['file'] = 'data-'.$version.'.sql';

            if ($data_count1 != $data_count2)
            {
                $return['error'] = 1;
            }
        }

        return $return;
    }

    public function runPluginSQL( $type, $prefix, $files = array(), $path, $settings = array() )
    {
        $db = ConnectionManager::getDataSource( 'default' );

        $counts = array();
        $error = false;

        if ( $type == 'install' && !empty( $settings['title'] ) && !empty( $settings['model_title'] ) ) {
            $this->loadModel('Module');

            if ( $data = $this->Module->findByTitle( $settings['title'] ) ) {
                $module_id = $data['Module']['id'];
            } else {
                $this->Module->create();

                if ( !empty( $settings['block_active'] ) && $settings['block_active'] == 1 ) {
                    $data['Module']['block_active'] = 1;
                } else {
                    $data['Module']['block_active'] = 0;
                }

                if ( !empty( $settings['is_searchable'] ) && $settings['is_searchable'] == 1 ) {
                    $data['Module']['is_searchable'] = 1;
                } else {
                    $data['Module']['is_searchable'] = 0;
                }

                if ( !empty( $settings['is_fields'] ) && $settings['is_fields'] == 1 ) {
                    $data['Module']['is_fields'] = 1;
                } else {
                    $data['Module']['is_fields'] = 0;
                }

                $data['Module']['title'] = $settings['title'];
                $data['Module']['model_title'] = $settings['model_title'];
                $data['Module']['is_plugin'] = 1;

                if ( !$this->Module->save( $data ) ) {
                    $error = 1;
                } else {
                    $module_id = $this->Module->id;
                }
            }
        } elseif ( $type = 'uninstall' && !empty( $settings['title'] ) && !empty( $settings['model_title'] ) ) {
            $this->loadModel('Module');

            if ( $data = $this->Module->findByTitle( $settings['title'] ) ) {
                $module_id = $data['Module']['id'];

                if ( !$this->Module->delete( $data['Module']['id'] ) ) {
                    $error = 1;
                }
            }
        }

        if (empty($module_id))
        {
            $module_id = 0;
        }
        
        $this->loadModel('Role');
        
        if ($type == 'uninstall')
        {
            $this->Role->Permission->deleteAll( array('Permission.plugin' => Inflector::humanize($settings['title'])) );
        }
        elseif ($type = 'install')
        {
            if (!empty( $settings['permissions']['file'] ) &&
                !empty( $settings['permissions']['className'] ) &&
                !empty( $settings['permissions']['functionName'] ) &&
                file_exists( $path . $settings['permissions']['file'] ))
            {
                $className = $settings['permissions']['className'];
                $functionName = $settings['permissions']['functionName'];
                $roles = $this->Role->find('all');
                
                include_once( $path . $settings['permissions']['file'] );
                
                $class = new $className();
                
                $permissions = $class->$functionName($roles, $module_id);
                
                if (!empty($permissions))
                {
                    if (!$this->Role->Permission->saveMany($permissions))
                    {
                        $error = "Couldn't save permissions";
                    }
                }
            }
        }

        if ( !empty( $files ) && !$error ) {
            foreach( $files as $file ) {
                if ( file_exists( $path . $file ) ) {
                    $sql = file_get_contents( $path . $file );
                    $success_count = 0;
                    $total_count = 0;

                    if ( !empty( $sql ) ) {
                        $queries = explode( '-- --------------------------------------------------------', $sql );

                        foreach( $queries as $query ) {
                            if ( !empty( $query ) ) {
                                $matches = array(
                                    '{prefix}',
                                    '{module_id}',
                                    '{date}'
                                    );
                                $replaces = array(
                                    $prefix,
                                    $module_id,
                                    date('Y-m-d H:i:s')
                                    );
                                $query = str_replace( $matches, $replaces, $query );

                                if ( $db->rawQuery( $query ) ) {
                                    $success_count++;
                                }

                                $total_count++;
                            }
                        }

                        $counts[$file]['total'] = $total_count;
                        $counts[$file]['success'] = $success_count;

                        if ($total_count != $success_count) {
                            $error = 1;
                        }
                    }
                }
            }
        }

        return array(
            'sql' => $counts,
            'error' => $error
            );
    }

    public function generateCipherSeed()
    {
        $length = 30;

        return substr(mt_rand(9999,99999999) * time().mt_rand(9999,99999999) * time(),0,$length);
    }

    public function generateSecuritySalt() {
        $length = 39;
        $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWZYZabcdefghijklmnopqrstuvwxyz";
        $real_string_legnth = strlen($characters) - 1;
        $string = "";

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $real_string_legnth)];
        }

        return $string;
    }

    public function install_plugin( $plugin )
    {
        $this->set('title_for_layout', 'AdaptCMS :: Plugin Install');

        $new_path = APP . 'Plugin' . DS . $plugin . DS;
        $path = APP . 'Old_Plugins' . DS . $plugin . DS;
        $data = array();
        $sql_files = array();

        if ( file_exists( $path . 'plugin.json' ) ) {
            $file_data = file_get_contents( $path . 'plugin.json' );
            $data = json_decode( $file_data, true );

            if ( !empty( $data['install']['text'] ) && file_exists( $path . $data['install']['text'] ) ) {
                $this->set( 'install_text', file_get_contents( $path . $data['install']['text'] ) );
            }

            if ( !empty( $data['install']['sql'] ) ) {
                $sql_files = $data['install']['sql'];
            }
        }

        if ( !empty( $this->request->data ) ) {
            if (!is_writable($path))
            {
                $this->set('error', 'Please chmod this folder recursively to 777 - ' . $path);
            }
            elseif (!is_writable(APP . 'Plugin'))
            {
                $this->set('error', 'Please chmod this folder recursively to 777 - ' . APP . 'Plugin');
            }
            else
            {
                $prefix = ConnectionManager::enumConnectionObjects();
                $settings = array();

                if ( !empty( $data['install']['block_active'] ) ) {
                    $settings['block_active'] = $data['install']['block_active'];
                }

                if ( !empty( $data['install']['is_searchable'] ) ) {
                    $settings['is_searchable'] = $data['install']['is_searchable'];
                }

                if ( !empty( $data['install']['is_fields'] ) ) {
                    $settings['is_fields'] = $data['install']['is_fields'];
                }

                if ( !empty( $data['install']['model_title'] ) ) {
                    $settings['model_title'] = $data['install']['model_title'];
                }

                if ( !empty( $data['install']['title'] ) ) {
                    $settings['title'] = $data['install']['title'];
                } elseif ( !empty( $data['title'] )) {
                    $settings['title'] = $data['title'];
                }

                if ( !empty( $data['install']['permissions']) )
                {
                    $settings['permissions'] = $data['install']['permissions'];
                }

                $sql = $this->runPluginSQL( 'install', $prefix['default']['prefix'], $sql_files, $path, $settings );

                $this->set( compact( 'sql' ) );

                if ( empty( $sql['error'] ) ) {
                    if ( !file_exists($new_path) && mkdir( $new_path, 0775 ) ) {
                        rename( $path, $new_path );
                    }
                    else
                    {
                        $this->loadModel('User');
                        $this->User->recursiveDelete($new_path);
                        rename( $path, $new_path );
                    }

                    if ( !empty($data['components']) )
                    {
                        $system_path = realpath(CACHE . '/../system/');

                        if (file_exists($system_path . '/components.json'))
                        {
                            $components_array = json_decode( file_get_contents($system_path . '/components.json'), true );
                            $add = array_keys($data['components']);

                            foreach($components_array as $key => $component)
                            {
                                if (!is_numeric($key) && $key == $add[0] || $component == $add[0])
                                {
                                    $component_exists = 1;
                                }
                            }

                            if (empty($component_exists))
                            {
                                $components_array[$add[0]] = $data['components'][$add[0]];
                            }

                            $fh = fopen($system_path . '/components.json', 'w') or die("can't open file");
                            fwrite($fh, json_encode($components_array));
                            fclose($fh);
                        }
                    }

                    if ( !empty($data['helpers']) )
                    {
                        $system_path = realpath(CACHE . '/../system/');

                        if (file_exists($system_path . '/helpers.json'))
                        {
                            $helpers_array = json_decode( file_get_contents($system_path . '/helpers.json'), true );
                            $add = array_keys($data['helpers']);

                            foreach($helpers_array as $key => $helper)
                            {
                                if (!is_numeric($key) && $key == $add[0] || $helper == $add[0])
                                {
                                    $helper_exists = 1;
                                }
                            }

                            if (empty($helper_exists))
                            {
                                $helpers_array[$add[0]] = $data['helpers'][$add[0]];
                            }

                            $fh = fopen($system_path . '/helpers.json', 'w') or die("can't open file");
                            fwrite($fh, json_encode($helpers_array));
                            fclose($fh);
                        }
                    }

                    $this->Session->setFlash( 'SQL has been inserted successfully.', 'flash_success' );
                } else {
                    $this->Session->setFlash( 'SQL could not be inserted.', 'flash_error' );
                }
            }
        }
    }

    public function uninstall_plugin( $plugin )
    {
        $this->set('title_for_layout', 'AdaptCMS :: Plugin Un-Install');

        $new_path = APP . 'Old_Plugins' . DS . $plugin . DS;
        $path = APP . 'Plugin' . DS . $plugin . DS;
        $data = array();
        $sql_files = array();

        if ( file_exists( $path . 'plugin.json' ) ) {
            $file_data = file_get_contents( $path . 'plugin.json' );
            $data = json_decode( $file_data, true );

            if ( !empty( $data['uninstall']['text'] ) && file_exists( $path . $data['uninstall']['text'] ) ) {
                $this->set( 'uninstall_text', file_get_contents( $path . $data['uninstall']['text'] ) );
            }

            if ( !empty( $data['uninstall']['sql'] ) ) {
                $sql_files = $data['uninstall']['sql'];
            }
        }

        if ( !empty( $this->request->data ) ) {
            $prefix = ConnectionManager::enumConnectionObjects();
            $settings = array();

            if ( !empty( $data['install']['block_active'] ) ) {
                $settings['block_active'] = $data['install']['block_active'];
            }

            if ( !empty( $data['install']['is_searchable'] ) ) {
                $settings['is_searchable'] = $data['install']['is_searchable'];
            }

            if ( !empty( $data['install']['is_fields'] ) ) {
                $settings['is_fields'] = $data['install']['is_fields'];
            }

            if ( !empty( $data['install']['model_title'] ) ) {
                $settings['model_title'] = $data['install']['model_title'];
            }

            if ( !empty( $data['install']['title'] ) ) {
                $settings['title'] = $data['install']['title'];
            } elseif ( !empty( $data['title'] )) {
                $settings['title'] = $data['title'];
            }

            $sql = $this->runPluginSQL( 'uninstall', $prefix['default']['prefix'], $sql_files, $path, $settings );

            $this->set( compact( 'sql' ) );

            if ( empty( $sql['error'] ) ) {
                if ( mkdir( $new_path, 0775 ) ) {
                    rename( $path, $new_path );
                }

                if ( !empty($data['components']) )
                {
                    $system_path = realpath(CACHE . '/../system/');

                    if (file_exists($system_path . '/components.json'))
                    {
                        $components_array = json_decode( file_get_contents($system_path . '/components.json'), true );
                        $remove = array_keys($data['components']);

                        foreach($components_array as $key => $component)
                        {
                            if (!is_numeric($key) && $key == $remove[0] || $component == $remove[0])
                            {
                                unset($components_array[$key]);
                            }
                        }

                        $fh = fopen($system_path . '/components.json', 'w') or die("can't open file");
                        fwrite($fh, json_encode($components_array));
                        fclose($fh);
                    }
                }

                if ( !empty($data['helpers']) )
                {
                    $system_path = realpath(CACHE . '/../system/');

                    if (file_exists($system_path . '/helpers.json'))
                    {
                        $helpers_array = json_decode( file_get_contents($system_path . '/helpers.json'), true );
                        $remove = array_keys($data['helpers']);

                        foreach($helpers_array as $key => $helper)
                        {
                            if (!is_numeric($key) && $key == $remove[0] || $helper == $remove[0])
                            {
                                unset($helpers_array[$key]);
                            }
                        }

                        $fh = fopen($system_path . '/helpers.json', 'w') or die("can't open file");
                        fwrite($fh, json_encode($helpers_array));
                        fclose($fh);
                    }
                }

                $this->Session->setFlash( 'SQL has been removed successfully.', 'flash_success' );
            } else {
                $this->Session->setFlash( 'SQL could not be removed.', 'flash_error' );
            }
        }
    }

    public function upgrade_plugin( $plugin )
    {
        $this->set('title_for_layout', 'AdaptCMS :: Plugin Upgrade');

        $path = APP . 'Plugin' . DS . $plugin . DS;

        if ( file_exists( $path . 'plugin.json' ) ) {
            $file_data = file_get_contents( $path . 'plugin.json' );
            $data = json_decode( $file_data, true );

            if ( !empty( $data['upgrade']['text'] ) && file_exists( $path . $data['upgrade']['text'] ) ) {
                $this->set( 'upgrade_text', file_get_contents( $path . $data['upgrade']['text'] ) );
            }

            if ( !empty( $data['upgrade']['sql'] ) ) {
                $sql_files = $data['upgrade']['sql'];
            }
        }

        if ( !empty( $this->request->data ) ) {
            $prefix = ConnectionManager::enumConnectionObjects();

            $sql = $this->runPluginSQL( 'upgrade', $prefix['default']['prefix'], $sql_files, $path, array() );

            $this->set( compact( 'sql' ) );

            if ( empty( $sql['error'] ) ) {
                try {
                    unlink( $path . DS . 'Install' . DS . 'upgrade.json' );

                    $this->Session->setFlash( 'SQL has been inserted successfully.', 'flash_success' );
                    clearCache(null, 'persistent');
                } catch(Exception $e)
                {
                    $error = $path . DS . 'Install' . DS . 'upgrade.json';
                }
            } else {
                $error = 'SQL Error';
            }

            if ( !empty($error) )
            {
                $this->set( compact( 'error' ) );
                $this->Session->setFlash( 'SQL could not be inserted.', 'flash_error' );
            }
        }
    }

    public function install_theme( $theme )
    {
        $this->set('title_for_layout', 'AdaptCMS :: Theme Install');

        $new_path = VIEW_PATH . 'Themed' . DS . $theme . DS;
        $path = VIEW_PATH . 'Old_Themed' . DS . $theme . DS;
        $data = array();
        $sql_files = array();

        if ( file_exists( $path . 'theme.json' ) ) {
            $file_data = file_get_contents( $path . 'theme.json' );
            $data = json_decode( $file_data, true );

            if ( !empty( $data['install']['text'] ) && file_exists( $path . $data['install']['text'] ) ) {
                $this->set( 'install_text', file_get_contents( $path . $data['install']['text'] ) );
            }

            if ( !empty( $data['install']['sql'] ) ) {
                $sql_files = $data['install']['sql'];
            }
        }

        if ( !empty( $this->request->data ) ) {
            if (!is_writable($path))
            {
                $this->set('error', 'Please chmod this folder recursively to 777 - ' . $path);
            }
            elseif (!is_writable(VIEW_PATH . 'Themed'))
            {
                $this->set('error', 'Please chmod this folder recursively to 777 - ' . VIEW_PATH . 'Themed');
            }
            else
            {
                $prefix = ConnectionManager::enumConnectionObjects();
                $settings = array();

                if ( !empty( $data['install']['title'] ) ) {
                    $settings['title'] = $data['install']['title'];
                } elseif ( !empty( $data['title'] )) {
                    $settings['title'] = $data['title'];
                } else {
                    $settings['title'] = $theme;
                }

                if (!empty($data['install']['afterInstallFilter']))
                    $settings['afterInstallFilter'] = $data['install']['afterInstallFilter'];

                $sql = $this->runThemeSQL( 'install', $prefix['default']['prefix'], $sql_files, $path, $settings );

                $this->set( compact( 'sql' ) );

                if ( empty( $sql['error'] ) ) {
                    if ( !file_exists($new_path) && mkdir( $new_path, 0775 ) ) {
                        rename( $path, $new_path );
                    }
                    else
                    {
                        $this->loadModel('User');
                        $this->User->recursiveDelete($new_path);
                        rename( $path, $new_path );
                    }

                    $this->Session->setFlash( 'SQL has been inserted successfully.', 'flash_success' );
                } else {
                    $this->Session->setFlash( 'SQL could not be inserted.', 'flash_error' );
                }
            }
        }
    }

    public function uninstall_theme( $theme )
    {
        $this->set('title_for_layout', 'AdaptCMS :: Theme Un-Install');

        $new_path = VIEW_PATH . 'Old_Themed' . DS . $theme . DS;
        $path = VIEW_PATH . 'Themed' . DS . $theme . DS;
        $data = array();
        $sql_files = array();

        if ( file_exists( $path . 'theme.json' ) ) {
            $file_data = file_get_contents( $path . 'theme.json' );
            $data = json_decode( $file_data, true );

            if ( !empty( $data['uninstall']['text'] ) && file_exists( $path . $data['uninstall']['text'] ) ) {
                $this->set( 'uninstall_text', file_get_contents( $path . $data['uninstall']['text'] ) );
            }

            if ( !empty( $data['uninstall']['sql'] ) ) {
                $sql_files = $data['uninstall']['sql'];
            }
        }

        if ( !empty( $this->request->data ) ) {
            $prefix = ConnectionManager::enumConnectionObjects();
            $settings = array();

            if ( !empty( $data['install']['title'] ) ) {
                $settings['title'] = $data['install']['title'];
            } elseif ( !empty( $data['title'] )) {
                $settings['title'] = $data['title'];
            } else {
                $settings['title'] = $theme;
            }

            if (!empty($data['install']['afterUninstallFilter']))
                $settings['afterUninstallFilter'] = $data['install']['afterUninstallFilter'];

            $sql = $this->runThemeSQL( 'uninstall', $prefix['default']['prefix'], $sql_files, $path, $settings );

            $this->set( compact( 'sql' ) );

            if ( empty( $sql['error'] ) ) {
                if ( mkdir( $new_path, 0775 ) ) {
                    rename( $path, $new_path );
                }

                $this->Session->setFlash( 'SQL has been removed successfully.', 'flash_success' );
            } else {
                $this->Session->setFlash( 'SQL could not be removed.', 'flash_error' );
            }
        }
    }

    public function upgrade_theme( $theme )
    {
        $this->set('title_for_layout', 'AdaptCMS :: Theme Upgrade');

        $path = VIEW_PATH . 'Themed' . DS . $theme . DS;

        if ( file_exists( $path . 'theme.json' ) ) {
            $file_data = file_get_contents( $path . 'theme.json' );
            $data = json_decode( $file_data, true );

            if ( !empty( $data['upgrade']['text'] ) && file_exists( $path . $data['upgrade']['text'] ) ) {
                $this->set( 'upgrade_text', file_get_contents( $path . $data['upgrade']['text'] ) );
            }

            if ( !empty( $data['upgrade']['sql'] ) ) {
                $sql_files = $data['upgrade']['sql'];
            }
        }

        if ( !empty( $this->request->data ) ) {
            $prefix = ConnectionManager::enumConnectionObjects();

            $sql = $this->runThemeSQL( 'upgrade', $prefix['default']['prefix'], $sql_files, $path, array() );

            $this->set( compact( 'sql' ) );

            if ( empty( $sql['error'] ) ) {
                try {
                    unlink( $path . DS . 'Install' . DS . 'upgrade.json' );

                    $this->Session->setFlash( 'SQL has been inserted successfully.', 'flash_success' );
                    clearCache(null, 'persistent');
                } catch(Exception $e)
                {
                    $error = $path . DS . 'Install' . DS . 'upgrade.json';
                }
            } else {
                $error = 'SQL Error';
            }

            if ( !empty($error) )
            {
                $this->set( compact( 'error' ) );
                $this->Session->setFlash( 'SQL could not be inserted.', 'flash_error' );
            }
        }
    }

    public function runThemeSQL( $type, $prefix, $files = array(), $path, $settings = array() )
    {
        $db = ConnectionManager::getDataSource( 'default' );

        $counts = array();
        $error = false;

        if ( $type == 'install' && !empty( $settings['title'] ) ) {
            $this->loadModel('Theme');

            if ( !$this->Theme->findByTitle( $settings['title'] ) ) {
                $this->Theme->create();

                $data['Theme']['title'] = $settings['title'];
                $data['Theme']['created'] = date('Y-m-d H:i:s');
                $data['Theme']['skipBeforeSave'] = true;

                if ( !$this->Theme->save( $data ) ) {
                    $error = 1;
                }
            }

            if (!empty( $settings['afterInstallFilter']['file'] ) &&
                !empty( $settings['afterInstallFilter']['className'] ) &&
                !empty( $settings['afterInstallFilter']['functionName'] ) &&
                file_exists( $path . $settings['afterInstallFilter']['file'] ))
            {
                $className = $settings['afterInstallFilter']['className'];
                $functionName = $settings['afterInstallFilter']['functionName'];

                include_once( $path . $settings['afterInstallFilter']['file'] );

                $class = new $className();

                $data = $class->$functionName();

                if (!empty($data['Categories']))
                {
                    $this->loadModel('User');

                    $categories = array();

                    foreach($data['Categories'] as $row)
                    {
                        $key = $row['title'];

                        $category = $this->User->Category->findByTitle($key);

                        if (!empty($category))
                        {
                            $categories[$key]['id'] = $category['Category']['id'];
                        }
                        else
                        {
                            $this->User->Category->create();

                            $data = array();

                            $data['Category']['title'] = $key;
                            $data['Category']['user_id'] = $this->Session->read('Auth.User.id');
                            $data['Category']['created'] = date('Y-m-d H:i:s');
                            $data['Category']['modified'] = date('Y-m-d H:i:s');

                            if ($this->User->Category->save($data))
                            {
                                $categories[$key]['id'] = $this->User->Category->id;
                            }
                        }

                        if (!empty($row['Field']))
                        {
                            foreach($row['Field'] as $field)
                            {
                                $field_lookup = $this->User->Category->Field->find('first', array(
                                    'conditions' => array(
                                        'Field.title' => $field['title'],
                                        'Category.id' => $categories[$key]['id']
                                    ),
                                    'contain' => array(
                                        'Category'
                                    )
                                ));

                                if (!empty($field_lookup))
                                {
                                    $categories[$key]['Field'][$field['title']] = $field_lookup['Field']['id'];
                                }
                                else
                                {
                                    $this->User->Category->Field->create();
                                    $data = array();

                                    if (!empty($field['description']))
                                        $data['Field']['description'] = $field['description'];

                                    if (isset($field['required']))
                                        $data['Field']['required'] = $field['required'];

                                    if (!empty($field['field_options']))
                                        $data['Field']['field_options'] = $field['field_options'];

                                    $data['Field']['title'] = $field['title'];
                                    $data['Field']['field_type'] = $field['field_type'];
                                    $data['Field']['user_id'] = $this->Session->read('Auth.User.id');
                                    $data['Field']['category_id'] = $categories[$key]['id'];
                                    $data['Field']['created'] = date('Y-m-d H:i:s');
                                    $data['Field']['modified'] = date('Y-m-d H:i:s');

                                    if ($this->User->Category->Field->save($data))
                                    {
                                        $categories[$key]['Field'][$data['Field']['title']] = $this->User->Category->Field->id;
                                    }
                                }
                            }
                        }

                        if (!empty($row['Article']))
                        {
                            foreach($row['Article'] as $article)
                            {
                                $this->User->Category->Article->create();
                                $data = array();

                                if (!empty($article['tags']))
                                    $data['Article']['tags'] = $article['tags'];

                                $data['Article']['title'] = $article['title'];
                                $data['Article']['status'] = 1;
                                $data['Article']['publish_time'] = date('Y-m-d H:i:s');
                                $data['Article']['created'] = date('Y-m-d H:i:s');
                                $data['Article']['modified'] = date('Y-m-d H:i:s');
                                $data['Article']['user_id'] = $this->Session->read('Auth.User.id');
                                $data['Article']['category_id'] = $categories[$key]['id'];

                                if ($this->User->Category->Article->save($data))
                                {
                                    $article_id = $this->User->Category->Article->id;
                                }

                                if (!empty($article_id) && !empty($article['ArticleValue']))
                                {
                                    foreach($article['ArticleValue'] as $value)
                                    {
                                        if (!empty($categories[$key]['Field'][$value['field_name']]))
                                        {
                                            $this->User->Category->Article->ArticleValue->create();
                                            $data = array();

                                            $data['ArticleValue']['data'] = $value['data'];
                                            $data['ArticleValue']['article_id'] = $article_id;
                                            $data['ArticleValue']['field_id'] = $categories[$key]['Field'][$value['field_name']];

                                            $this->User->Category->Article->ArticleValue->save($data);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } elseif ( $type = 'uninstall' && !empty( $settings['title'] ) ) {
            $this->loadModel('Theme');

            if ( $data = $this->Theme->findByTitle( $settings['title'] ) ) {
                if ( !$this->Theme->delete( $data['Theme']['id'] ) ) {
                    $error = 1;
                }
            }

            if (!empty( $settings['afterUninstallFilter']['file'] ) &&
                !empty( $settings['afterUninstallFilter']['className'] ) &&
                !empty( $settings['afterUninstallFilter']['functionName'] ) &&
                file_exists( $path . $settings['afterUninstallFilter']['file'] ))
            {
                $className = $settings['afterUninstallFilter']['className'];
                $functionName = $settings['afterUninstallFilter']['functionName'];

                include_once( $path . $settings['afterUninstallFilter']['file'] );

                $class = new $className();

                $data = $class->$functionName();
            }
        }

        if ( !empty( $files ) && !$error ) {
            foreach( $files as $file ) {
                if ( file_exists( $path . $file ) ) {
                    $sql = file_get_contents( $path . $file );
                    $success_count = 0;
                    $total_count = 0;

                    if ( !empty( $sql ) ) {
                        $queries = explode( '-- --------------------------------------------------------', $sql );

                        foreach( $queries as $query ) {
                            if ( !empty( $query ) ) {
                                $matches = array(
                                    '{prefix}',
                                    '{date}'
                                    );
                                $replaces = array(
                                    $prefix,
                                    date('Y-m-d H:i:s')
                                    );
                                $query = str_replace( $matches, $replaces, $query );

                                if ( $db->rawQuery( $query ) ) {
                                    $success_count++;
                                }

                                $total_count++;
                            }
                        }

                        $counts[$file]['total'] = $total_count;
                        $counts[$file]['success'] = $success_count;

                        if ($total_count != $success_count) {
                            $error = 1;
                        }
                    }
                }
            }
        }

        return array(
            'sql' => $counts,
            'error' => $error
            );
    }

    public function old()
    {
        if (!empty($this->params['type']))
        {
            $db = ConnectionManager::getDataSource( 'default' );

            if ($this->params['type'] == 'article' && !empty($this->params['slug']))
            {
                $this->loadModel('Article');

                $conditions = array(
                    'Article.deleted_time' => '0000-00-00 00:00:00',
                    'Article.slug' => $this->params['slug']
                );

                if (!empty($this->params['category']))
                {
                    $conditions['Category.title'] = $this->params['category'];
                }

                $find = $this->Article->find('first', array(
                    'conditions' => $conditions,
                    'contain' => array(
                        'Category'
                    )
                ));

                if (!empty($find['Article']['id']))
                {
                    $return = array(
                        'controller' => 'articles',
                        'action' => 'view',
                        'slug' => $find['Article']['slug'],
                        'id' => $find['Article']['id']
                    );
                }
            }

            if ($this->params['type'] == 'category' && !empty($this->params['slug']))
            {
                $this->loadModel('Category');

                $find = $this->Category->find('first', array(
                    'conditions' => array(
                        'Category.deleted_time' => '0000-00-00 00:00:00',
                        'Category.slug' => $this->params['slug']
                    )
                ));

                if (!empty($find['Category']['id']))
                {
                    $return = array(
                        'controller' => 'categories',
                        'action' => 'view',
                        $find['Category']['slug']
                    );
                }
            }

            if ($this->params['type'] == 'page' && !empty($this->params['slug']))
            {
                $this->loadModel('Page');

                $find = $this->Page->find('first', array(
                    'conditions' => array(
                        'Page.deleted_time' => '0000-00-00 00:00:00',
                        'Page.slug' => $this->params['slug']
                    )
                ));

                if (!empty($find['Page']['id']))
                {
                    $return = array(
                        'controller' => 'pages',
                        'action' => 'display',
                        $find['Page']['slug']
                    );
                }
            }

            if (!empty($return))
            {
                $this->redirect($return, array('status' => 301));
            }
            else
            {
                $this->Session->setFlash('The ' . $this->params['type'] . ' does not exist.', 'flash_error');
                $this->redirect('/');
            }
        }
    }
}