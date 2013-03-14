<?php

class ApiComponent extends Object
{
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

	private function url()
	{
		return 'http://api.adaptcoding.com/';
	}

	private function curlRequest($url)
	{
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $data = curl_exec($ch);
	    curl_close($ch);

	    return $data;
	}

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

	private function writeCache($file, $data)
	{
    	$fh = fopen($file, 'w') or die("can't open file");
    	fwrite($fh, $data);
    	fclose($fh);
	}

	private function checkCache($cache_file)
	{
		if (file_exists($cache_file)) {
			$cache_file_time = filemtime($cache_file);
			$new_cache_calc = time() - $cache_file_time;
		}

		if (empty($new_cache_calc) || !empty($new_cache_calc) && $new_cache_calc > 604800)
		{
			return true;
		} else {
			return false;
		}
	}

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