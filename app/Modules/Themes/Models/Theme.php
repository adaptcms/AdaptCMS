<?php

namespace App\Modules\Themes\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

use Artisan;
use Cache;
use Core;
use Storage;
use Zipper;

class Theme extends Model
{
    use Searchable,
        Sluggable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'themes';

    protected $fillable = [
        'name',
        'status'
    ];

    /**
    * User
    *
    * @return User
    */
    public function user()
    {
        return $this->belongsTo('App\Modules\Users\Models\User');
    }
    
    /**
    * Search Logic
    *
    * @param array $searchData
    *
    * @return array
    */
    public function searchLogic($searchData = [])
    {
        if (!empty($searchData['keyword'])) {
            $results = Theme::search($searchData['keyword'])->get();
            
            foreach ($results as $key => $row) {
                $results[$key]->url = route('admin.themes.edit', [ 'id' => $row->id ]);
            }
        } elseif(!empty($searchData['template_path'])) {
            $body = Storage::disk('themes')->get($searchData['template_path']);
        
            $results = [
                [
                    'body' => $body
                ]
            ];
        } else {
            $results = [];
        }

        return $results;
    }

    /**
    * Simple Save
    *
    * @param array $data
    *
    * @return array
    */
    public function simpleSave($data = [])
    {
        if (!empty($data['many'])) {
            $data['ids'] = json_decode($data['ids'], true);

            switch ($data['type']) {
                case 'delete':
                    Theme::whereIn('id', $data['ids'])->delete();
                break;
            }
        }

        return [
            'status' => true,
            'ids' => $data['ids']
        ];
    }

    /**
    * Add
    *
    * @param array $postArray
    *
    * @return Theme
    */
    public function add($postArray = [])
    {
        $this->name = $postArray['name'];
        $this->status = $postArray['status'];
        $this->user_id = $postArray['user_id'];
        $this->custom = 1;

        // enable via pkg manager
        $this->enable();

        $slug = $this->slug;

        $files = Storage::disk('themes')->allFiles('default');

        $paths = [
            $slug,
            $slug . '/assets',
            $slug . '/layouts',
            $slug . '/views',
            $slug . '/partials',
            $slug . '/views/albums',
            $slug . '/views/categories',
            $slug . '/views/pages',
            $slug . '/views/posts',
            $slug . '/views/tags',
            $slug . '/views/users',
            $slug . '/views/files'
        ];
        foreach ($paths as $path) {
            $full_path = public_path('themes/default') . str_replace($slug . '/', 'default/', $path);

            if (!is_link($full_path) && !Storage::disk('themes')->exists($path)) {
                Storage::disk('themes')->makeDirectory($path);
            }
        }

        foreach ($files as $file) {
            $new_path = str_replace('default', $slug, $file);

            if (!Storage::disk('themes')->exists($new_path)) {
                Storage::disk('themes')->copy($file, $new_path);
            }
        }

        $this->save();

        return $this;
    }

    /**
    * Edit
    *
    * @param array $postArray
    *
    * @return Theme
    */
    public function edit($postArray = [])
    {
        $old_slug = $this->slug;

        $this->name = $postArray['name'];
        $this->user_id = $postArray['user_id'];

        $this->save();

        if (Storage::disk('themes')->exists($old_slug)) {
            Storage::disk('themes')->move($old_slug, $this->slug);
        }

        // re-enable theme
        $this->enable();

        return $this;
    }

    /**
    * Delete
    *
    * @return bool
    */
    public function delete()
    {
        if ($this->id == 1) {
            return false;
        }

        if (Storage::disk('themes')->exists($this->slug)) {
            Storage::disk('themes')->deleteDirectory($this->slug);
        }

        return parent::delete();
    }

    /**
    * Get Config
    *
    * @param string $key
    *
    * @return string
    */
    public function getConfig($key)
    {
        $path = $this->slug . '/theme.json';
    
        // exists check
        if (!$file = Storage::disk('themes')->exists($path)) {
            return null;
        }
    
        // get the theme.json file
        $file = Storage::disk('themes')->get($path);
        $file = json_decode($file, true);

        return !isset($file[$key]) ? '' : $file[$key];
    }

    /**
    * Enable
    *
    * @return Theme
    */
    public function enable()
    {
        // try to find module
        $client = new Client;

        // get the module
        $res = $client->request('GET', Core::getMarketplaceApiUrl() . '/module/slug/theme/' . $this->slug, [ 'http_errors' => false ]);

        if ($res->getStatusCode() == 200) {
            $module = json_decode((string) $res->getBody(), true);

            if (!empty($module)) {
                // increment install count for module
                $client->request('GET', Core::getMarketplaceApiUrl() . '/install/' . $module['module_type'] . '/' . $module['slug'], [ 'http_errors' => false ]);
            }
        }

        try {
            $status = Artisan::call('vendor:publish', [
                '--all' => true
            ]);
        } catch(\Exception $e) {
            abort(500, 'Cannot publish module assets. Try chmodding the /public/assets/modules/ folder to 755 recursively.');
        }
        
        Cache::forget('theme_count');

        return $this;
    }

    /**
    * Disable
    *
    * @return Theme
    */
    public function disable()
    {
        try {
            $status = Artisan::call('vendor:publish', [
                '--all' => true
            ]);
        } catch(\Exception $e) {
            abort(500, 'Cannot publish module assets. Try chmodding the /public/assets/modules/ folder to 755 recursively.');
        }
        
        Cache::forget('theme_count');

        return $this;
    }

    /**
    * Install
    *
    * @param integer $id
    *
    * @return Theme
    */
    public function install($id)
    {
        $client = new Client();

        // get the theme
        $res = $client->request('GET', Core::getMarketplaceApiUrl() . '/module/' . $id, [ 'http_errors' => false ]);

        if ($res->getStatusCode() == 200) {
            $module = json_decode($res->getBody(), true);
        } else {
            abort(404);
        }

        // set slug
        $slug = ucfirst($module['slug']);

        // download the latest version
        $res = $client->request('GET', $module['latest_version']['download_url']);

        if ($res->getStatusCode() == 200) {
            $filename = $module['slug'] . '.zip';

            Storage::disk('themes')->put($filename, $res->getBody(), 'public');
        } else {
            abort(404);
        }

        // make the folder
        if (!Storage::disk('themes')->exists($module['slug'])) {
            Storage::disk('themes')->makeDirectory($module['slug']);
        }

        // then attempt to extract contents
        $path = public_path() . '/themes/' . $filename;
        $zip_folder = $module['module_type'] . '-' . $module['slug'] . '-' . $module['latest_version']['version'];

        Zipper::make($path)->folder($zip_folder)->extractTo(public_path() . '/themes');

        // delete the ZIP
        if (Storage::disk('themes')->exists($filename)) {
            Storage::disk('themes')->delete($filename);
        }

        // once we've gotten the files all setup
        // lets run the upgrade event with the version #, if it exists
        Core::fireEvent($slug, $slug . 'Update', $module['latest_version']['version']);

        // enable
        $this->enable();

        Cache::forever('theme_updates', 0);
        Cache::forget('themes_updates_list');

        return $this;
    }
    
    /**
    * Get Count
    *
    * @return integer
    */
    public static function getCount()
    {
        return Cache::remember('theme_count', 3600, function() {
            return Theme::count();
        });
    }

    /**
    * Sluggable
    * Return the sluggable configuration array for this model.
    *
    * @return array
    */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
