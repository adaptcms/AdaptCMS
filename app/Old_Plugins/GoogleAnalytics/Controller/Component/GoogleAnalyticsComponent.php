<?php
App::import('Vendor', 'GoogleAnalytics.gapi');
App::uses('Component', 'Controller');

class GoogleAnalyticsComponent extends Component
{
    public $name = 'GoogleAnalyticsComponent';
    private $ga;
    private $profile_id;

    public function __start()
    {
        if (empty($this->ga) || empty($this->profile_id))
        {
            if (Configure::read('GoogleAnalytics.email') && Configure::read('GoogleAnalytics.password') &&
                Configure::read('GoogleAnalytics.profile_id'))
            {
                $this->ga = new gapi(Configure::read('GoogleAnalytics.email'), Configure::read('GoogleAnalytics.password'));
                $this->profile_id = Configure::read('GoogleAnalytics.profile_id');
            }
        }
    }

    public function getTopKeywords($start_date, $end_date)
    {
        $this->__start();

        $cache_file = CACHE . DS.  'persistent' . DS . 'google-analytics-top-keywords-' . $start_date . $end_date . '.tmp';

        if ($this->checkCache($cache_file))
        {
            $this->ga->requestReportData(
                $this->profile_id,
                array('keyword'),
                array('pageviews'),
                '-pageviews',
                '',
                $start_date,
                $end_date,
                1,
                10
            );
            $results = $this->ga->getResults();

            $searches = array();
            foreach($results as $result)
            {
                $searches[] = array(
                   'keyword' => $result->getKeyword(),
                   'views' => $result->getPageviews()
                );
            }
            
            $this->writeCache($cache_file, $searches);
        }
        else
        {
            $searches = $this->getCache($cache_file);
        }

        return $searches;
    }

    public function getTopBrowsers($start_date, $end_date)
    {
        $this->__start();

        $cache_file = CACHE . DS.  'persistent' . DS . 'google-analytics-top-browsers-' . $start_date . $end_date . '.tmp';

        if ($this->checkCache($cache_file))
        {
           $this->ga->requestReportData(
               $this->profile_id,
               array('browser', 'browserVersion'),
               array('pageviews'),
               '-pageviews',
               '',
               $start_date,
               $end_date,
               1,
               10
            );
           $results = $this->ga->getResults();

           $browsers = array();
           foreach($results as $result)
           {
               $browsers[] = array(
                   'browser' => $result->getBrowser(),
                   'browserVersion' => $result->getBrowserversion(),
                   'views' => $result->getPageviews()
                );
           }

            $this->writeCache($cache_file, $browsers);
        }
        else
        {
            $browsers = $this->getCache($cache_file);
        }

       return $browsers;
    }

    public function getTopOperatingSystems($start_date, $end_date)
    {
        $this->__start();

        $cache_file = CACHE . DS.  'persistent' . DS . 'google-analytics-top-operating-systems-' . $start_date . $end_date . '.tmp';

        if ($this->checkCache($cache_file))
        {
            $this->ga->requestReportData(
               $this->profile_id,
               array('operatingSystem', 'operatingSystemVersion'),
               array('pageviews'),
               '-pageviews',
               '',
               $start_date,
               $end_date,
               1,
               10
            );
            $results = $this->ga->getResults();

            $operating_systems = array();
            foreach($results as $result)
            {
               $operating_systems[] = array(
                   'operatingSystem' => $result->getOperatingsystem(),
                   'operatingSystemVersion' => $result->getOperatingsystemversion(),
                   'views' => $result->getPageviews()
                );
            }

            $this->writeCache($cache_file, $operating_systems);
        }
        else
        {
            $operating_systems = $this->getCache($cache_file);
        }

       return $operating_systems;
    }

    public function getTopSources($start_date, $end_date)
    {
        $this->__start();

        $cache_file = CACHE . DS.  'persistent' . DS . 'google-analytics-top-sources-' . $start_date . $end_date . '.tmp';

        if ($this->checkCache($cache_file))
        {
            $this->ga->requestReportData(
               $this->profile_id,
               array('source','referralPath'),
               array('visits'),
               '-visits',
               '',
               $start_date,
               $end_date,
               1,
               10
            );
            $results = $this->ga->getResults();

            $sources = array();
            foreach($results as $result)
            {
               if ($result->getReferralPath() == "(not set)") {
                   $sources[] = array(
                       'link' => false,
                       'host' => $result->getSource(),
                       'visits' => $result->getVisits()
                    );
                } else {
                   $sources[] = array(
                        'link' => true,
                        'host' => $result->getSource() . $result->getReferralPath(),
                        'visits' => $result->getVisits()
                    );
                }
            }

            $this->writeCache($cache_file, $sources);
        }
        else
        {
            $sources = $this->getCache($cache_file);
        }

       return $sources;
    }

    public function getOverviewStats($start_date, $end_date)
    {
        $this->__start();

        $cache_file = CACHE . DS.  'persistent' . DS . 'google-analytics-overview-stats-' . $start_date . $end_date . '.tmp';

        if ($this->checkCache($cache_file))
        {
            $this->ga->requestReportData(
                $this->profile_id,
                array('date'),
                array('pageviews', 'visitors', 'uniquePageviews', 'pageviewsPerVisit', 'avgTimeOnPage', 'percentNewVisits', 'pageLoadTime', 'entranceBounceRate', 'exitRate'),
                'date',
                '',
                $start_date,
                $end_date
            );

            $results = $this->ga->getResults();

            $stats = array(
                'avgTimeOnPage' => 0,
                'percentNewVisits' => 0,
                'pageLoadTime' => 0,
                'visitors' => 0,
                'pageViews' => 0,
                'uniquePageviews' => 0,
                'pageViewsPerVisit' => 0,
                'entranceBounceRate' => 0,
                'exitRate' => 0
            );
            $views = array();

            foreach($results as $result)
            {
                $views[] = array(
                   'date' => date('M j',strtotime($result->getDate())),
                   'views' => $result->getPageviews(),
                   'uniques' => $result->getUniquePageViews()
                );

                $stats['avgTimeOnPage'] += $result->getAvgtimeonpage();
                $stats['percentNewVisits'] += $result->getPercentnewvisits();
                $stats['pageLoadTime'] += $result->getPageloadtime();

                $stats['visitors'] += $result->getVisitors();
                $stats['pageViews'] += $result->getPageviews();
                $stats['uniquePageviews'] += $result->getUniquePageViews();
                $stats['pageViewsPerVisit'] += $result->getPageviewspervisit();
                $stats['entranceBounceRate'] += $result->getEntrancebouncerate();
                $stats['exitRate'] += $result->getExitrate();
            }

            $stats['percentNewVisits'] = round($stats['percentNewVisits'] / count($results), 2);
            $stats['pageLoadTime'] = round(($stats['pageLoadTime'] / count($results) / 1000), 2);
            $stats['avgTimeOnPage'] = round($stats['avgTimeOnPage'] / count($results), 2);
            $stats['pageViewsPerVisit'] = round($stats['pageViewsPerVisit'] / count($results), 2);
            $stats['entranceBounceRate'] = round($stats['entranceBounceRate'] / count($results), 2);
            $stats['exitRate'] = round($stats['exitRate'] / count($results), 2);

            $mins = floor($stats['avgTimeOnPage'] / 60);
            $seconds = ($stats['avgTimeOnPage'] % 60);
            $avg_time = '';

            if (!empty($mins))
            {
                $avg_time .= $mins . ' minute(s)';
            }

            if (!empty($seconds) && !empty($mins))
            {
                $avg_time .= ', ';
            }

            if (!empty($seconds))
            {
                $avg_time .= $seconds . ' seconds';
            }

            $stats['avgTimeOnPage'] = $avg_time;

            $data = array(
                'stats' => $stats,
                'views' => $views
            );
        }
        else
        {
            $data = $this->getCache($cache_file);
        }

        return $data;
    }

    /**
     * Writes cache to specified file with specified data
     * 
     * @param string $file
     * @param string $data
     */
    private function writeCache($file, $data)
    {
        if (is_array($data))
        {
            $data = json_encode($data);
        }

        $fh = fopen($file, 'w') or die("can't open file");
        fwrite($fh, $data);
        fclose($fh);
    }

    /**
     * Gets content of cache file
     * 
     * @param string $file
     * @return array
     */
    private function getCache($file)
    {
        if (file_exists($file) && is_readable($file))
        {
            $contents = file_get_contents($file);

            if (!empty($contents))
                return json_decode($contents, true);
        }

        return array();
    }

    /**
     * Looks to see if new cache needs to be written
     * 
     * @param string $cache_file
     * @return boolean
     */
    private function checkCache($cache_file, $time_diff = 86400)
    {
        if (file_exists($cache_file)) {
            $cache_file_time = filemtime($cache_file);
            $new_cache_calc = time() - $cache_file_time;
        }

        if (empty($new_cache_calc) || !empty($new_cache_calc) && $new_cache_calc > $time_diff)
        {
            return true;
        } else {
            return false;
        }
    }
}