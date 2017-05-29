<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Notifications\Notifiable;
use GuzzleHttp\Client;

use App\Modules\Themes\Models\Theme;

use Cache;
use Settings;
use Storage;
use Module;
use Core;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Notifiable;

    public $apiUrl;

    public $template_header = "@extends('layouts.base')
@section('content')" . PHP_EOL;
    public $template_footer = PHP_EOL . '@stop';

    public function __construct()
    {
        $this->apiUrl = Core::getMarketplaceApiUrl();

        // CMS Update Checks
        $this->checkForCmsUpdates();
        $this->checkForPluginUpdates();
        $this->checkForThemeUpdates();

        $this->syncWebsite();
    }

    public function checkForCmsUpdates()
    {
        $apiUrl = $this->apiUrl;

        Cache::remember('core_cms_updates', Settings::get('check_for_updates_every_x_minutes', 15), function() use($apiUrl) {
            $cms_updates = 0;

            $client = new Client();

            // get the cms versions
            $res = $client->request('GET', $apiUrl . '/cms/versions', [ 'http_errors' => false ]);

            if ($res->getStatusCode() == 200) {
                $versions = json_decode($res->getBody(), true);
            } else {
                return false;
            }

            // try to get the installed version
    				// from the API and the latest
            $current_version = array_where($versions, function($val, $key) {
                return $val['branch_slug'] == Core::getVersion() ? $val : false;
            });
            $current_version = reset($current_version);

            // sort the versions by ID ASC
            // flip it to DESC order
            // grab the latest one
            $latest_version = array_reverse(array_sort($versions, function($value) {
                return $value['id'];
            }));
            $latest_version = reset($latest_version);

			// if empty somehow, 404
			if (empty($current_version) || empty($latest_version)) {
					return false;
			}

            Cache::forever('bleedinge_edge_update', 0);

            // for new installs, let's set it up
            if (!$this->getCommitHash()) {
                // and lastly, set the latest commit hash and commit version data
                $this->setCommitHash($current_version['commit_hash']);
                Cache::forever('cms_current_version', json_encode($current_version));
            } elseif ($current_version['commit_hash'] != $this->getCommitHash()) {
                // if it's not the most recent
                // increment the notification value for bleeding edge

                if (env('APP_DEBUG')) {
                    $cms_updates++;
                    Cache::forever('bleedinge_edge_update', 1);

                    Cache::forever('cms_current_version', json_encode($current_version));
                }
            }

            // check for normal upgrades
            if ($current_version['id'] != $latest_version['id']) {
                $cms_updates++;

                Cache::forever('cms_latest_version_name', $latest_version['version']);
                Cache::forever('cms_latest_version', json_encode($latest_version));
            }

            Cache::forever('cms_updates', $cms_updates);

            return true;
        });
    }

    public function checkForPluginUpdates()
    {
        $apiUrl = $this->apiUrl;

        Cache::remember('plugin_updates', Settings::get('check_for_updates_every_x_minutes', 15), function() use($apiUrl) {
            // set the client
            $client = new Client();

            $plugins = Module::all();

            $plugin_updates = 0;

            // empty out module updates data
            $modules_updates_list = [];
            foreach($plugins as $plugin) {
                // get the module
                $res = $client->request('GET', $apiUrl . '/module/slug/plugin/' . $plugin['slug'], [ 'http_errors' => false ]);

                if ($res->getStatusCode() == 200) {
                    $module = json_decode($res->getBody(), true);
                } else {
                    continue;
                }

                if ($module['latest_version']['version'] != Module::get($plugin['slug'] . '::version')) {
                    $plugin_updates++;

                    // add module for updates index
                    $modules_updates_list[] = $module;
                }
            }

            Cache::forever('plugin_updates', $plugin_updates);

            Cache::forever('plugins_updates_list', json_encode($modules_updates_list));

            return true;
        });
    }

    public function checkForThemeUpdates()
    {
        $apiUrl = $this->apiUrl;

        Cache::remember('theme_updates', Settings::get('check_for_updates_every_x_minutes', 15), function() use($apiUrl) {
            // set the client
            $client = new Client();

            $themes = Theme::all();

            $theme_updates = 0;
            $modules_updates_list = [];
            foreach($themes as $theme) {
                // get the module
                $res = $client->request('GET', $apiUrl . '/module/slug/theme/' . $theme['slug'], [ 'http_errors' => false ]);

                if ($res->getStatusCode() == 200) {
                    $module = json_decode($res->getBody(), true);
                } else {
                    continue;
                }

                if ($module['latest_version']['version'] != $theme->getConfig('version')) {
                    $theme_updates++;

                    $module['theme'] = $theme;

                    // add module for updates index
                    $modules_updates_list[] = $module;
                }
            }

            Cache::forever('theme_updates', $theme_updates);
            Cache::forever('themes_updates_list', json_encode($modules_updates_list));

            return true;
        });
    }

    public function syncWebsite()
    {
          // every 3 days
          $minutes = (1440 * 3);
          Cache::remember('sync_website', $minutes, function() {
              Core::syncWebsite();

              return true;
          });
    }

    public function fireEvent($module, $class = '', $arg = '')
    {
        $class = '\App\Modules\\' . $module . '\\Events\\' . $class;

        if (class_exists($class)) {
            if (!empty($arg)) {
                event(new $class($arg));
            } else {
                event(new $class);
            }
        }
    }

    public function setCommitHash($hash)
    {
        return Storage::disk('base')->put('.commit_hash', $hash, 'public');
    }

    public function getCommitHash()
    {
        return Storage::disk('base')->get('.commit_hash');
    }
}
