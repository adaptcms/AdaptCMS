<?php

class ApiComponent extends Object
{
    /**
     * Name of Component
     * 
     * @var string 
     */
    public $name = 'Api';
    
    public function initialize()
    {

    }

    public function shutdown()
    {

    }

    public function beforeRender()
    {

    }

    public function beforeRedirect()
    {

    }

    public function startup()
    {

    }

    /**
     * URL to API Website
     * 
     * @return string
     */
    private function url()
    {
        return 'http://api.adaptcoding.com/';
    }

    /**
     * Makes CURL request to specified URL and returns response data
     * 
     * @param string url
     * @return array
     */
    private function curlRequest($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    /**
     * Takes array of keys/values and turns into url arguments
     * 
     * @param array $args
     * @return string
     */
    private function getArgs($args)
    {
        if (empty($args))
        {
            return;
        }

        if (is_array($args)) {
            $i = 0;
            foreach($args as $key => $arg) {
                if ($i == 0) {
                        $this->args .= "?";
                } else {
                        $this->args .= "&";
                }

                $this->args .= $key."=".urlencode($arg);

                $i++;
            }

            return $this->args;
        }
    }

    /**
     * Writes cache to specified file with specified data
     * 
     * @param string $file
     * @param string $data
     */
    private function writeCache($file, $data)
    {
        $fh = fopen($file, 'w') or die("can't open file");
        fwrite($fh, $data);
        fclose($fh);
    }

    /**
     * Looks to see if new cache needs to be written - if file doesn't exist or was last modified past 3 days
     * 
     * @param string $cache_file
     * @return boolean
     */
    private function checkCache($cache_file)
    {
        if (file_exists($cache_file)) {
            $cache_file_time = filemtime($cache_file);
            $new_cache_calc = time() - $cache_file_time;
        }

        if (empty($new_cache_calc) || !empty($new_cache_calc) && $new_cache_calc > 259200)
        {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Takes array of Plugin IDS and does lookup on API website, returns back data and writes to file
     * 
     * @param array $ids
     * @return array or false on failure to write cache
     */
    public function pluginsLookup($ids = array())
    {
        $cache_file = CACHE . DS.  'persistent' . DS . 'plugin-version-check-' . implode("-",$ids) . '.tmp';

        if ($this->checkCache($cache_file))
        {
            $data = $this->curlRequest(
                $this->url() . 'get/plugin-version-check?ids=' . implode(",",$ids)
            );

            if (!empty($data)) {
                $this->writeCache($cache_file, $data);

                return json_decode($data, true);
            } else {
                return false;
            }
        } else {
            return json_decode(
                file_get_contents($cache_file),
                true
            );
        }
    }

        /**
     * Takes array of Theme IDS and does lookup on API website, returns back data and writes to file
     * 
     * @param array $ids
     * @return array or false on failure to write cache
     */
    public function themesLookup($ids = array())
    {
        $cache_file = CACHE . DS.  'persistent' . DS . 'theme-version-check-' . implode("-",$ids) . '.tmp';

        if ($this->checkCache($cache_file))
        {
            $data = $this->curlRequest(
                $this->url() . 'get/theme-version-check?ids=' . implode(",",$ids)
            );

            if (!empty($data)) {
                $this->writeCache($cache_file, $data);

                return json_decode($data, true);
            } else {
                return false;
            }
        } else {
            return json_decode(
                file_get_contents($cache_file),
                true
            );
        }
    }

    /**
     * Looks up AdaptCMS.com articles
     * 
     * @param integer $limit
     * @param string $category
     * @return array or false on failure to write cache
     */
    public function getSiteArticles($limit = 5, $category)
    {
        $cache_file = CACHE . DS.  'persistent' . DS . 'get-site-' . $category . '-' . $limit . '.tmp';

        if ($this->checkCache($cache_file))
        {
            if (Configure::read('api_key') != '{api_key}')
            {
                $data['api_key'] = Configure::read('api_key');
                $data['api_secret'] = Configure::read('api_secret');
            } else {
                $data = array();
            }

            $data = $this->curlRequest(
                $this->url() . 'get/site-' . $category . '/' . $limit . $this->getArgs($data)
            );

            if (!empty($data)) {
                $this->writeCache($cache_file, $data);

                return json_decode($data, true);
            } else {
                return false;
            }
        } else {
            return json_decode(
                file_get_contents($cache_file),
                true
            );
        }
    }

    /**
     * Gets plugins from API website based on parameters supplied
     * 
     * @param integer $limit
     * @param string $sort_by
     * @param string $sort_dir
     * @return array or false on failure to write cache
     */
    public function getPlugins($limit = 5, $sort_by = 'id', $sort_dir = 'desc')
    {
        $cache_file = CACHE . DS.  'persistent' . DS . 'get-plugins-' . $limit . '.tmp';
        $options = array(
            'limit' => $limit,
            'sort_by' => $sort_by,
            'sort_dir' => $sort_dir
        );

        if ($this->checkCache($cache_file))
        {
            if (Configure::read('api_key') != '{api_key}')
            {
                $options['api_key'] = Configure::read('api_key');
                $options['api_secret'] = Configure::read('api_secret');
            }

            $data = $this->curlRequest(
                $this->url() . 'get/plugins' . $this->getArgs($options)
            );

            if (!empty($data)) {
                $this->writeCache($cache_file, $data);

                return json_decode($data, true);
            } else {
                return false;
            }
        } else {
            return json_decode(
                file_get_contents($cache_file),
                true
            );
        }
    }

        /**
     * Gets themes from API website based on parameters supplied
     * 
     * @param integer $limit
     * @param string $sort_by
     * @param string $sort_dir
     * @return array or false on failure to write cache
     */
    public function getThemes($limit = 5, $sort_by = 'id', $sort_dir = 'desc')
    {
        $cache_file = CACHE . DS.  'persistent' . DS . 'get-themes-' . $limit . '.tmp';
        $options = array(
            'limit' => $limit,
            'sort_by' => $sort_by,
            'sort_dir' => $sort_dir
        );

        if ($this->checkCache($cache_file))
        {
            if (Configure::read('api_key') != '{api_key}')
            {
                $options['api_key'] = Configure::read('api_key');
                $options['api_secret'] = Configure::read('api_secret');
            }

            $data = $this->curlRequest(
                $this->url() . 'get/themes' . $this->getArgs($options)
            );

            if (!empty($data)) {
                $this->writeCache($cache_file, $data);

                return json_decode($data, true);
            } else {
                    return false;
            }
        } else {
            return json_decode(
                file_get_contents($cache_file),
                true
            );
        }
    }
}