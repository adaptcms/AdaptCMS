<?php

namespace App\Modules\Posts\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

use Cache;
use Storage;

class Page extends Model implements Sortable
{
    use Searchable,
        Sluggable,
        SortableTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pages';

    protected $fillable = [
        'name',
        'body',
        'status',
        'meta_keywords',
        'meta_description'
    ];

    public $sortable = [
        'order_column_name' => 'ord',
        'sort_when_creating' => true,
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
    * Add
    *
    * @param array $postArray
    *
    * @return Page
    */
    public function add($postArray = [])
    {
        $this->name = $postArray['name'];

        if (!empty($postArray['slug'])) {
            $this->slug = $postArray['slug'];
        }

        $this->user_id = $postArray['user_id'];
        $this->status = $postArray['status'];
        $this->body = $postArray['body'];
        $this->meta_keywords = $postArray['meta_keywords'];
        $this->meta_description = $postArray['meta_description'];

        $this->save();

        Storage::disk('themes')->put(Cache::get('theme', 'default') . '/views/pages/' . $this->slug . '.blade.php', $this->body);

        return $this;
    }

    /**
    * Edit
    *
    * @param array $postArray
    *
    * @return Page
    */
    public function edit($postArray = [])
    {
        $this->name = $postArray['name'];

        if (!empty($postArray['slug'])) {
            $this->slug = $postArray['slug'];
        }
        
        $this->user_id = $postArray['user_id'];
        $this->status = $postArray['status'];
        $this->body = $postArray['body'];
        $this->meta_keywords = $postArray['meta_keywords'];
        $this->meta_description = $postArray['meta_description'];

        $this->save();

        Storage::disk('themes')->put(Cache::get('theme', 'default') . '/views/pages/' . $this->slug . '.blade.php', $this->body);

        return $this;
    }

    /**
    * Delete
    *
    * @return bool
    */
    public function delete()
    {
        $path = Cache::get('theme', 'default') . '/views/pages/' . $this->slug . '.blade.php';
        if (Storage::disk('themes')->exists($path)) {
            Storage::disk('themes')->delete($path);
        }

        return parent::delete();
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

            switch($data['type']) {
                case 'delete':
                    Page::whereIn('id', $data['ids'])->delete();
                break;

                case 'toggle-statuses':
                    $active_items = Page::whereIn('id', $data['ids'])->where('status', '=', 1)->get();
                    $pending_items = Page::whereIn('id', $data['ids'])->where('status', '=', 0)->get();

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

    /**
    * Search Logic
    *
    * @param array $searchData
    * @param bool $admin
    *
    * @return array
    */
    public function searchLogic($searchData = [], $admin = false)
    {
        if (!empty($searchData['keyword'])) {
            $results = Page::search($searchData['keyword'])->get();
        } elseif(!empty($searchData['id'])) {
            $results = [ Page::find($searchData['id']) ];
        } else {
            $results = [];
        }

        foreach($results as $key => $row) {
            if ($admin) {
                $results[$key]->url = route('admin.pages.edit', [ 'id' => $row->id ]);
            } else {
                $results[$key]->url = route('pages.view', [ 'slug' => $row->slug ]);
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
