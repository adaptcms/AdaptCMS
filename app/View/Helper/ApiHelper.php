<?php

class ApiHelper extends AppHelper
{
    /**
     * Name of the helper
     * 
     * @var string
     */
    public $name = 'Api';
    
    /**
     * Array of Helpers used below
     * 
     * @var array
     */
    public $helpers = array(
        'Html',
        'Js',
        'Session'
    );

    /**
     * The URL to the API Website at AdaptCMS
     * 
     * @return string
     */
    public function url()
    {
        return Configure::read('Component.Api.api_url');
    }
    
    /**
     * The URL to the AdaptCMS Website
     * 
     * @return string
     */
    public function siteUrl()
    {
        return Configure::read('Component.Api.adaptcms_url');
    }

    /**
     * Slug specified string
     * 
     * @param string $str
     * @return string
     */
    public function slug($str)
    {
        return strtolower(Inflector::slug($str, '-'));
    }

    /**
     * Unused JSONP function that does a call to the API, we use CURL instead.
     *
     * @param string $action
     * @param string $data
     * @param string $return
     * @param boolean $if_cached
     * @return string
     */
    public function construct_jsonp($action, $data, $return, $if_cached = null)
    {
        return "<script type='text/javascript'>
                    $(document).ready(function() {
                            $.getJSON('" . $this->url() . "v1/" . $action . "?callback=?', " . json_encode( $data ) . ", function(data) {
                        " . $return . "
                      });
                    });
            </script>";
    }

    /**
     * Takes array of keys/values and transforms them into a url of arguments.
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

        return;
    }

    /**
     * Writes cache to specified file with specified data
     *
     * @param string $file
     * @param string $data
     * @return none
     */
    public function write_cache($file, $data)
    {
        $fh = fopen($file, 'w') or die("can't open file");
        fwrite($fh, $data);
        fclose($fh);
    }

    /**
     * CURL Function that makes call to API website and returns response.
     * 
     * @param string $action
     * @param array $data
     * @return array
     */
    public function construct_curl($action, $data)
    {
        if (Configure::read('api_key') != '{api_key}')
        {
            $data['api_key'] = Configure::read('api_key');
            $data['api_secret'] = Configure::read('api_secret');
        }

        $url = $this->url() . "v1/" . $action . $this->getArgs($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    /**
     * Checks version of AdaptCMS and returns back a status - Current Version, Need to Upgrade or error - couldn't connect to server.
     * 
     * @return string
     */
    public function version_check()
    {
        $cache_file = CACHE . DS.  'persistent' . DS . 'version-check.tmp';
        $icon = array(
            1 => ADAPTCMS_VERSION . " <i class='icon icon-ok' title='Your version is current'></i>",
            0 => ADAPTCMS_VERSION . " <a href='http://www.adaptcms.com/update' target='_new'> <i class='icon icon-ban-circle' title='You need to upgrade, click for details.'></i></a>",
            'error' => ADAPTCMS_VERSION . " <i class='icon icon-question-sign' title='Could not connect to server'></i>"
        );

        if (file_exists($cache_file))
        {
            $cache_file_time = filemtime($cache_file);
            $new_cache_calc = time() - $cache_file_time;
        }

        // 259200 = 3 days
        if (empty($new_cache_calc) || !empty($new_cache_calc) && $new_cache_calc > 259200)
        {
            if (Configure::read('api_key') == '{api_key}' && !strstr(Router::url('/', true), 'localhost') && !strstr(Router::url('/', true), '127.0.0.1'))
            {
                $get_key = $this->construct_curl(
                    'key',
                    array(
                        'version' => ADAPTCMS_VERSION,
                        'url' => Router::url('/', true)
                    )
                );
                $key = json_decode($get_key, true);

                if (!empty($key['data']['api_key']) && !empty($key['data']['api_secret']))
                {
                    $file = APP.'Config/config.php';
                    $config_file = file_get_contents($file);

                    $matches = array(
                            '{api_key}',
                            '{api_secret}'
                    );
                    $replace = array(
                            $key['data']['api_key'],
                            $key['data']['api_secret']
                    );

                    $contents = str_replace($matches, $replace, $config_file);

                    $fh = fopen($file, 'w') or die("can't open file");
                    fwrite($fh, $contents);
                    fclose($fh);
                }
            }

            $data = $this->construct_curl(
                'version-check',
                array(
                    'version' => $this->slug(ADAPTCMS_VERSION)
                )
            );

            if (!$data) {
                $this->write_cache($cache_file, 'error');

                return $icon['error'];
            } else {
                $data = json_decode($data);
                $version = $data->version->current_version;

                $this->write_cache($cache_file, $version);

                return $icon[$version];
            }
        } else {
            $status = file_get_contents($cache_file);

            return $icon[$status];
        }
        /*
        if ($status = $this->Session->read('Api.version-check.status')) {
                return $icon[$status];
        } else {
                $this->Session->write('Api.version-check.status', 0);

                return $this->construct_jsonp(
                        'version-check',
                        array(
                                'version' => $this->slug(ADAPTCMS_VERSION)
                        ),
                        'if (data.error) {
                                $("#version-check").html("' . $icon['error'] . '");
                        } else if (data.version.id) {
                                if (data.version.current_version == 1) {
                                        $("#version-check").html("' . $icon[0] . '");
                                } else {
                                        $("#version-check").html("' . $icon[1] . '");
                                }
                        }
                        '
                );
        }
        */
    }
}