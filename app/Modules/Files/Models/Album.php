<?php

namespace App\Modules\Files\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Cviebrock\EloquentSluggable\Sluggable;
use RyanWeber\Mutators\Timezoned;

class Album extends Model
{
    use Searchable,
        Sluggable,
        Timezoned;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'albums';

    protected $fillable = [
        'name',
        'user_id'
    ];

    /**
    * Album Files
    *
    * @return Collection
    */
    public function albumFiles()
    {
        return $this->hasMany('App\Modules\Files\Models\AlbumFile');
    }

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
                    Album::whereIn('id', $data['ids'])->delete();
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
            $results = Album::search($searchData['keyword'])->get();
        } else {
            $results = [];
        }

        foreach($results as $key => $row) {
            if ($admin) {
                $results[$key]->url = route('admin.albums.edit', [ 'id' => $row->id ]);
            } else {
                $results[$key]->url = route('albums.view', [ 'slug' => $row->slug ]);
            }
        }

        return $results;
    }

    /**
    * Add
    *
    * @param array $postArray
    *
    * @return Album
    */
    public function add($postArray = [])
    {
        $this->name = $postArray['name'];
        $this->user_id = $postArray['user_id'];

        $this->save();

        return $this;
    }

    /**
    * Edit
    *
    * @param array $postArray
    *
    * @return Album
    */
    public function edit($postArray = [])
    {
        $this->name = $postArray['name'];
        $this->user_id = $postArray['user_id'];

        $this->save();

        return $this;
    }

    /**
    * Get File Count
    *
    * @return integer
    */
    public function getFileCount()
    {
        return AlbumFile::where('album_id', '=', $this->id)->count();
    }

    /**
    * Get Newest File
    *
    * @return File|null
    */
    public function getNewestFile()
    {
        return $this->albumFiles->count() ?
            $this->albumFiles()->orderBy('created_at', 'desc')->first()->file : null;
    }

    /**
    * Get Files
    *
    * @param bool $paginated
    *
    * @return Collection
    */
    public function getFiles($paginated = true)
    {
        $files = [];
        if ($this->albumFiles->count()) {
            if ($paginated) {
                $files = $this->albumFiles()->paginate(15);
            } else {
                $files = $this->albumFiles;
            }
        }

        return $files;
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
