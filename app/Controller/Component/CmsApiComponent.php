<?php
/**
 * Class CmsApiComponent
 */
class CmsApiComponent extends Object
{
    /**
     * Name of Component
     * 
     * @var string 
     */
    public $name = 'CmsApi';
    
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
        return Configure::read('Component.Api.api_url');
    }

    /**
     * Makes CURL request to specified URL and returns response data
     * 
     * @param string $url
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
     * Writes cache to specified file with specified data
     *
     * @param string $file
     * @param string $data
     */
    private function _writeCache($file, $data)
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
    private function _checkCache($cache_file)
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
     * Takes array of keys/values and turns into url arguments
     * 
     * @param array $args
     * @return string
     */
    private function getArgs($args)
    {
        if (!empty($args) && is_array($args))
        {
            $url = '';
            $i = 0;

            foreach($args as $key => $arg)
            {
                if ($i == 0)
                {
                    $url .= "?";
                } else {
                    $url .= "&";
                }

                $url .= $key."=".urlencode($arg);

                $i++;
            }

            return $url;
        }

        return false;
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

        if ($this->_checkCache($cache_file))
        {
            $data = $this->curlRequest(
                $this->url() . 'get/plugin-version-check?ids=' . implode(",",$ids)
            );

            if (!empty($data)) {
                $this->_writeCache($cache_file, $data);

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

    /*
    public function pluginsLookup($ids = array())
    {
        $cache_file = 'plugin-version-check-' . implode("-",$ids);

        if ($data = Cache::read($cache_file))
        {
            return json_decode($data, true);
        }
        else
        {
            $data = $this->curlRequest(
                $this->url() . 'v1/plugin-version-check?ids=' . implode(",",$ids)
            );

            if (!empty($data)) {
                Cache::write($cache_file, $data, 'api');

                return json_decode($data, true);
            } else {
                return false;
            }
        }
    }
    */

    /**
     * Takes array of Theme IDS and does lookup on API website, returns back data and writes to file
     *
     * @param array $ids
     * @return array or false on failure to write cache
     */
    public function themesLookup($ids = array())
    {
        $cache_file = CACHE . DS.  'persistent' . DS . 'theme-version-check-' . implode("-",$ids) . '.tmp';

        if ($this->_checkCache($cache_file))
        {
            $data = $this->curlRequest(
                $this->url() . 'get/theme-version-check?ids=' . implode(",",$ids)
            );

            if (!empty($data)) {
                $this->_writeCache($cache_file, $data);

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

    /*
    public function themesLookup($ids = array())
    {
        $cache_file = 'theme-version-check-' . implode("-",$ids);

        if ($data = Cache::read($cache_file))
        {
            return json_decode($data, true);
        }
        else
        {
            $data = $this->curlRequest(
                $this->url() . 'v1/theme-version-check?ids=' . implode(",",$ids)
            );

            if (!empty($data)) {
                Cache::write($cache_file, $data, 'api');

                return json_decode($data, true);
            } else {
                return false;
            }
        }
    }
    */

    /**
     * Looks up AdaptCMS.com articles
     *
     * @param integer $limit
     * @param string $category
     * @return array or false on failure to write cache
     */
    public function getSiteArticles($limit = 5, $category)
    {
        $cache_file = 'get-site-' . $category . '-' . $limit;

        if ($data = Cache::read($cache_file))
        {
            return json_decode($data, true);
        }
        else
        {
            $data = array();

            if (Configure::read('api_key') != '{api_key}')
            {
                $data['api_key'] = Configure::read('api_key');
                $data['api_secret'] = Configure::read('api_secret');
            }

            $data = $this->curlRequest(
                $this->url() . 'v1/site-' . $category . '/' . $limit . $this->getArgs($data)
            );

            if (!empty($data)) {
                Cache::write($cache_file, $data, 'api');

                return json_decode($data, true);
            } else {
                return false;
            }
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
        $cache_file = 'get-plugins-' . $limit;

        $options = array(
            'limit' => $limit,
            'sort_by' => $sort_by,
            'sort_dir' => $sort_dir
        );

        if ($data = Cache::read($cache_file))
        {
            return json_decode($data, true);
        }
        else
        {
            if (Configure::read('api_key') != '{api_key}')
            {
                $options['api_key'] = Configure::read('api_key');
                $options['api_secret'] = Configure::read('api_secret');
            }

            $data = $this->curlRequest(
                $this->url() . 'v1/plugins' . $this->getArgs($options)
            );

            if (!empty($data)) {
                Cache::write($cache_file, $data, 'api');

                return json_decode($data, true);
            } else {
                return false;
            }
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
        $cache_file = 'get-themes-' . $limit;

        $options = array(
            'limit' => $limit,
            'sort_by' => $sort_by,
            'sort_dir' => $sort_dir
        );

        if ($data = Cache::read($cache_file))
        {
            return json_decode($data, true);
        }
        else
        {
            if (Configure::read('api_key') != '{api_key}')
            {
                $options['api_key'] = Configure::read('api_key');
                $options['api_secret'] = Configure::read('api_secret');
            }

            $data = $this->curlRequest(
                $this->url() . 'v1/themes' . $this->getArgs($options)
            );

            if (!empty($data)) {
                Cache::write($cache_file, $data, 'api');

                return json_decode($data, true);
            } else {
                    return false;
            }
        }
    }

    /**
     * Get Admin Data
     *
     * @return bool|mixed
     */
    public function getAdminData()
    {
        $cache_file = 'admin-data';

        if ($data = Cache::read($cache_file))
        {
            return json_decode($data, true);
        }
        else
        {
            if (Configure::read('api_key') != '{api_key}')
            {
                $options['api_key'] = Configure::read('api_key');
                $options['api_secret'] = Configure::read('api_secret');
            }

            $data = $this->curlRequest(
                $this->url() . 'v1/admin-data'
            );

            if (!empty($data))
            {
                Cache::write($cache_file, $data, 'api');

                $temp_data = json_decode($data, true);

                return $temp_data['data'];
            } else {
                return false;
            }
        }
    }
}