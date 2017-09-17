<?php

namespace App\Modules\Themes\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use GuzzleHttp\Client;

use Storage;
use Artisan;
use Core;
use Cache;
use Zipper;

class Theme extends Model
{
    use Searchable;

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

    public function user()
    {
        return $this->belongsTo('App\Modules\Users\Models\User');
    }

    public function searchLogic($searchData)
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

    public function simpleSave($data)
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

    public function add($postArray)
    {
        $this->name = $postArray['name'];
        $this->status = $postArray['status'];
        $this->user_id = $postArray['user_id'];

        $slug = str_slug($this->name, '-');

        $this->slug = $slug;
        $this->custom = 1;

        // enable via pkg manager
        $this->enable();

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

    public function edit($postArray)
    {
        $old_slug = $this->slug;

        $this->name = $postArray['name'];

        if ($this->id > 1) {
            $this->slug = str_slug($this->name, '-');
        }

        $this->user_id = $postArray['user_id'];

        $this->save();

        if (Storage::disk('themes')->exists($old_slug)) {
            Storage::disk('themes')->move($old_slug, $this->slug);
        }

        // re-enable theme
        $this->enable();

        return $this;
    }

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

    public function getConfig($key)
    {
        // get the theme.json file
        $file = Storage::disk('themes')->get($this->slug . '/theme.json');
        $file = json_decode($file, true);

        return !isset($file[$key]) ? '' : $file[$key];
    }

	/**
	* Enable
	*
	* @return void
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
            $status = Artisan::call('vendor:publish');
        } catch(\Exception $e) {
            abort(500, 'Cannot publish module assets. Try chmodding the /public/assets/modules/ folder to 755 recursively.');
        }
        
        Cache::forget('theme_count');
    }

	/**
	* Disable
	*
	* @return void
	*/
    public function disable()
    {
        try {
            $status = Artisan::call('vendor:publish');
        } catch(\Exception $e) {
            abort(500, 'Cannot publish module assets. Try chmodding the /public/assets/modules/ folder to 755 recursively.');
        }
        
        Cache::forget('theme_count');
    }

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
    }
    
    /**
    * Get Count
    *
    * @return int
    */
    public static function getCount()
    {
    	return Cache::remember('theme_count', 3600, function() {
    		return Theme::count();
    	});
    }
}
