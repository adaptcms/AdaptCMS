<?php

namespace App\Modules\Themes\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

use Storage;
use Artisan;

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
        } else {
            $results = [];
        }

        foreach ($results as $key => $row) {
            $results[$key]->url = route('admin.themes.edit', [ 'id' => $row->id ]);
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

                case 'toggle-statuses':
                    $active_themes = Theme::whereIn('id', $data['ids'])->where('status', '=', 1)->get();
                    $pending_themes = Theme::whereIn('id', $data['ids'])->where('status', '=', 0)->get();

                    foreach ($active_themes as $theme) {
                        $theme->status = 0;

                        $theme->save();
                    }

                    foreach ($pending_themes as $theme) {
                        $theme->status = 1;

                        $theme->save();
                    }
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

    public function enable()
    {
        try {
            $status = Artisan::call('vendor:publish');
        } catch(\Exception $e) {
            abort(500, 'Cannot publish module assets. Try chmodding the /public/assets/modules/ folder to 755 recursively.');
        }
    }

    public function disable()
    {
        try {
            $status = Artisan::call('vendor:publish');
        } catch(\Exception $e) {
            abort(500, 'Cannot publish module assets. Try chmodding the /public/assets/modules/ folder to 755 recursively.');
        }
    }
}
