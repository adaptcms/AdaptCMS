<?php

class GoogleAnalyticsController extends AppController
{
	public $name = 'GoogleAnalytics';
	public $uses = array();
	public $components = array(
		'GoogleAnalytics.GoogleAnalytics'
	);

	public function admin_index()
	{
		if (!Configure::read('GoogleAnalytics.email') || !Configure::read('GoogleAnalytics.password') ||
			!Configure::read('GoogleAnalytics.profile_id'))
		{
			$this->set('text', true);
		}
		else
		{
			if (!empty($this->request->query['data']['GoogleAnalytics']['start_date']))
			{
				$start = explode('-', $this->request->query['data']['GoogleAnalytics']['start_date']);
				$start_date = $start[2] . '-' . $start[0] . '-' . $start[1];
			}
			else
			{
				$start_date = date('Y-m-d', strtotime('-14 day'));
			}

			if (!empty($this->request->query['data']['GoogleAnalytics']['end_date']))
			{
				$end = explode('-', $this->request->query['data']['GoogleAnalytics']['end_date']);
				$end_date = $end[2] . '-' . $end[0] . '-' . $end[1];
			}
			else
			{
				$end_date = date('Y-m-d', strtotime('-1 day'));
			}

	        $searches = $this->GoogleAnalytics->getTopKeywords($start_date, $end_date);
	        $sources = $this->GoogleAnalytics->getTopSources($start_date, $end_date);
	        $browsers = $this->GoogleAnalytics->getTopBrowsers($start_date, $end_date);
	        $operating_systems = $this->GoogleAnalytics->getTopOperatingSystems($start_date, $end_date);

	        $data = $this->GoogleAnalytics->getOverviewStats($start_date, $end_date);
	        $stats = $data['stats'];
	        $views = $data['views'];

	        $this->set(
	        	compact(
	        		'sources', 
	        		'views', 
	        		'searches', 
	        		'stats', 
	        		'start_date', 
	        		'end_date', 
	        		'browsers', 
	        		'operating_systems'
	        	)
	        );
	    }
	}
}