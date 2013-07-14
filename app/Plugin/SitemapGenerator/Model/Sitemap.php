<?php

class Sitemap extends AppModel
{
	public $name = 'Sitemap';
	public $useTable = false;

	public function generateSitemap()
	{
		$view = new View();
		
		$path = APP . '/Plugin/SitemapGenerator' . DS . 'webroot' . DS;
		$sitemap_path = $path . 'sitemap.xml';
		$urllist_path = $path . 'urllist.txt';

		$this->Article = Classregistry::init('Article');
		
		$articles = $this->Article->find('all');
		$categories = $this->Article->Category->find('all');
		$libraries = Classregistry::init('Media')->find('all');
		$pages = Classregistry::init('Page')->find('all', array(
            'conditions' => array(
                'Page.slug !=' => 'home'
            )
        ));
		$users = Classregistry::init('User')->find('all');

		$data = array(
			'articles' => $articles,
			'categories' => $categories,
			'libraries' => $libraries,
			'pages' => $pages,
			'users' => $users
		);

		$urllist = $view->element('SitemapGenerator.urllist', array('data' => $data));
		$sitemap = $view->element('SitemapGenerator.sitemap', array('data' => $data));

    	$fh = fopen($sitemap_path, 'w') or die("can't open file");
    	fwrite($fh, $sitemap);
    	fclose($fh);

    	$fh = fopen($urllist_path, 'w') or die("can't open file");
    	fwrite($fh, $urllist);
    	fclose($fh);

    	if (!strstr(Router::url('/', true), 'localhost') && !strstr(Router::url('/', true), '127.0.0.1'))
    	{
	    	$sitemap_url = Router::url('/', true) . 'SitemapGenerator' . DS . 'sitemap.xml';
	    	$urllist_url = Router::url('/', true) . 'SitemapGenerator' . DS . 'urllist.txt';

	    	$services = array(
	    		'http://www.google.com/webmasters/sitemaps/ping?sitemap=' . $sitemap_url
	    	);

			if ($yahoo_key = Configure::read('SitemapGenerator.yahoo_sitemap_key'))
			{
				$services[] = 'http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?appid=' . $yahoo_key . '&url=' . $sitemap_url;
				$services[] = 'http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?appid=' . $yahoo_key . '&url=' . $urllist_url;
			}

			foreach($services as $service)
			{
				$ping = fopen($service, 'r');
				fread($ping, 8192);
				fclose($ping);
			}
		}
	}

	public function onSettingsUpdate($old_settings = array(), $new_settings = array())
	{
		$this->Cron = Classregistry::init('Cron');

		if (isset($new_settings['number_of_days_between_generation']) &&
			isset($old_settings['number_of_days_between_generation']) &&
			$new_settings['number_of_days_between_generation'] != $old_settings['number_of_days_between_generation'] && 
			is_numeric($new_settings['number_of_days_between_generation']))
		{
			$cron = $this->Cron->findByFunction('generateSitemap');

			if (!empty($cron))
			{
				$data = array();

				$data['id'] = $cron['Cron']['id'];
				$data['period_amount'] = $new_settings['number_of_days_between_generation'];
				$data['run_time'] = '0000-00-00 00:00:00';

				$this->Cron->save($data);
			}
		}
	}
}