<?php
namespace App\Modules\Plugins\Models;

use Storage;
use Artisan;
use Cache;

class Plugin
{
    public static $core_modules = [
        'Core' => 'Core',
        'Files' => 'Files',
        'Modules' => 'Modules',
        'Plugins' => 'Plugins',
        'Posts' => 'Posts',
        'Themes' => 'Themes',
        'Users' => 'Users'
    ];

    public static $plugins_json;
    public static $modules = [];

    public static function init()
    {
        self::$plugins_json = storage_path('app') . '/modules.json';

        if (!self::$modules) {
            self::$modules = Cache::remember('modules', 60, function() {
                $file = Storage::disk('local')->get('modules.json');

                if (empty($file)) {
                    abort(404, 'Cannot find json file at line ' . __LINE__);
                }

                return json_decode($file, true);
            });
        }
    }

    public static function enable($slug)
    {
        self::init();
        self::buildJson($slug, true);

        try {
            $status = Artisan::call('vendor:publish');
        } catch(\Exception $e) {
            abort(500, 'Cannot publish module assets. Try chmodding the /public/assets/modules/ folder to 755 recursively.');
        }
    }

    public static function disable($slug)
    {
        self::init();
        self::buildJson($slug, false);
    }

    public static function buildJson($slug, $enabled = true)
    {
        // if not created yet at all, create it
        if (!isset(self::$modules[$slug])) {
            $plugin_json = Storage::disk('plugins')->get($slug . '/module.json');

            // file doesn't exist?
            if (empty($plugin_json)) {
                abort(404, 'Cannot find json file at line ' . __LINE__);
            }

            $plugin_json = json_decode($plugin_json, true);

            // file corrupt?
            if (empty($plugin_json)) {
                abort(404, 'Cannot find valid json content at line ' . __LINE__);
            }

            // at this point we have - name, slug, version (latest), description
            // let's build the rest
            $plugin_json['basename'] = $plugin_json['name'];
            $plugin_json['id'] = time() + rand(1, 50);
            $plugin_json['enabled'] = $enabled;
            $plugin_json['order'] = 9001;

            self::$modules[$slug] = $plugin_json;

            // write to module.json
            Storage::disk('plugins')->put($slug . '/module.json', json_encode($plugins_json), 'public');

            // write to global modules.json file
            Storage::disk('local')->put('modules.json', json_encode(self::$modules), 'public');
        } else {
            self::$modules[$slug]['enabled'] = $enabled;

            Storage::disk('local')->put('modules.json', json_encode(self::$modules), 'public');
        }
    }

    public static function getCoreModules()
    {
        // 1 day
        $minutes = (60 * 24);

        return Cache::remember('core_modules', $minutes, function() {
            if (Cache::get('cms_current_version')) {
                $current_version = json_decode(Cache::get('cms_current_version'), true);

                $core_modules = $current_version['core_modules'];
            } else {
                $core_modules = self::$core_modules;
            }

            return $core_modules;
        });
    }
}
