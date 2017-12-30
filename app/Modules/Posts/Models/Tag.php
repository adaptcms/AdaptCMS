<?php

namespace App\Modules\Posts\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Cviebrock\EloquentSluggable\Sluggable;

use Storage;
use Cache;

class Tag extends Model
{
	use Searchable,
        Sluggable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tags';

    protected $fillable = [
    	'name',
    	'body',
        'meta_keywords',
        'meta_description'
    ];

    public function user()
    {
	    return $this->belongsTo('App\Modules\Users\Models\User');
    }

    public function postTags()
    {
        return $this->hasMany('App\Modules\Posts\Models\PostTag');
    }

    public function add($postArray)
    {
	    $this->name = $postArray['name'];
	    $this->user_id = $postArray['user_id'];
		$this->meta_keywords = $postArray['meta_keywords'];
        $this->meta_description = $postArray['meta_description'];

	    $this->save();

		// store the contents
		$path = Cache::get('theme', 'default') . '/views/tags/';
		if (!Storage::disk('themes')->exists($path . $this->slug . '.blade.php')) {
			Storage::disk('themes')->copy($path . 'view.blade.php', $path . $this->slug . '.blade.php');
		}

		return $this;
    }

    public function edit($postArray)
    {
	    $this->name = $postArray['name'];
	    $this->user_id = $postArray['user_id'];
		$this->meta_keywords = $postArray['meta_keywords'];
        $this->meta_description = $postArray['meta_description'];

	    $this->save();

		// store the contents
		$path = Cache::get('theme', 'default') . '/views/tags/';
		if (!Storage::disk('themes')->exists($path . $this->slug . '.blade.php')) {
	        Storage::disk('themes')->copy($path . 'view.blade.php', $path . $this->slug . '.blade.php');
		}

		return $this;
	}

    public function simpleSave($data)
    {
        if (!empty($data['many'])) {
            $data['ids'] = json_decode($data['ids'], true);

            switch($data['type']) {
	            case 'delete':
	                Tag::whereIn('id', $data['ids'])->delete();
	                break;

	            case 'toggle-statuses':
	                $active_items = Tag::whereIn('id', $data['ids'])->where('status', '=', 1)->get();
	                $pending_items = Tag::whereIn('id', $data['ids'])->where('status', '=', 0)->get();

	                foreach($active_items as $item) {
	                    $item->status = 0;

	                    $item->save();
	                }

	                foreach($pending_items as $item) {
	                    $item->status = 1;

	                    $item->save();
	                }

                break;
            }
        }

        return [
            'status' => true,
            'ids' => $data['ids']
        ];
    }

    public function delete()
    {
	    $path = Cache::get('theme', 'default') . '/views/tags/' . $this->slug . '.blade.php';
		if (Storage::disk('themes')->exists($path)) {
			Storage::disk('themes')->delete($path);
		}

	    return parent::delete();
    }

    public function searchLogic($searchData, $admin = false)
    {
        if (!empty($searchData['keyword'])) {
            $results = Tag::search($searchData['keyword'])->get();
        } else {
            $results = [];
        }

        foreach($results as $key => $row) {
            if ($admin) {
                $results[$key]->url = route('admin.tags.edit', [ 'id' => $row->id ]);
            } else {
                $results[$key]->url = route('tags.view', [ 'slug' => $row->slug ]);
            }
        }

        return $results;
    }

    /**
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
