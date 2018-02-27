<?php

namespace App\Modules\Files\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

use App\Modules\Files\Models\AlbumFile;

class File extends Model
{
    use Searchable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'files';

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
    * Get Related Val
    *
    * @return array
    */
    public function getRelatedVal()
    {
        $albums = [];
        foreach($this->albumFiles as $row) {
            $albums[] = $row->album_id;
        }

        return $albums;
    }

    /**
    * Get Files
    *
    * @return array
    */
    public static function getFiles()
    {
        return self::pluck('filename', 'id');
    }

    /**
    * Get Images
    *
    * @return Query
    */
    public static function getImages()
    {
        $imageTypes = [
            'gif',
            'jpeg',
            'jpg',
            'png'
        ];

        $query = File::where(function($q) use($imageTypes) {
            foreach($imageTypes as $type) {
                $q->orWhere('file_type', '=', $type);
            }
        });

        return $query->pluck('filename', 'id');
    }

    /**
    * Get File Types
    *
    * @return array
    */
    public static function getFileTypes()
    {
        $file_types_tmp = File::pluck('file_type')->groupBy('file_type');
        $file_types = [];
        foreach($file_types_tmp as $file) {
            foreach($file as $row) {
                $file_types[$row] = $row;
            }
        }

        asort($file_types);

        return $file_types;
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
                    File::whereIn('id', $data['ids'])->delete();
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
    *
    * @return array
    */
    public function searchLogic($searchData = [])
    {
        if (!empty($searchData['keyword'])) {
            $results = File::search($searchData['keyword'])->get();
        } else {
            $results = [];
        }

        foreach($results as $key => $row) {
            $results[$key]->url = route('admin.files.edit', [ 'id' => $row->id ]);
        }

        return $results;
    }

    /**
    * Add
    *
    * @param array $postArray
    * @param UploadedFile $file
    * @param array $albums
    *
    * @return File
    */
    public function add($postArray = [], $file, $albums = [])
    {
        $filename = time() . '-' . $file->getClientOriginalName();

        $path = $file->store('files', 'files');

        // save file
        $this->user_id = $postArray['user_id'];
        $this->filename = $filename;
        $this->file_type = $file->extension();
        $this->path = '/' . $path;

        $this->save();

        // save album files
        if (!empty($albums)) {
            foreach($albums as $row) {
                $album = new AlbumFile;

                $album->file_id = $this->id;
                $album->album_id = $row;

                $album->save();
            }
        }

        return $this;
    }

    /**
    * Edit
    *
    * @param array $postArray
    * @param UploadedFile $file
    * @param array $albums
    *
    * @return File
    */
    public function edit($postArray = [], $file, $albums = [])
    {
        if (!empty($postArray['validFile'])) {
            $filename = time() . '-' . $file->getClientOriginalName();

            $path = $file->store('files', 'files');

            $this->filename = $filename;
            $this->file_type = $file->extension();
            $this->path = '/' . $path;
        }


        // save file
        $this->user_id = $postArray['user_id'];

        $this->save();

        // save album files
        AlbumFile::where('file_id', '=', $this->id)->delete();

        if (!empty($albums)) {
            foreach($albums as $row) {
                $album = new AlbumFile;

                $album->file_id = $this->id;
                $album->album_id = $row;

                $album->save();
            }
        }

        return $this;
    }

    /**
    * Delete
    *
    * @return bool
    */
    public function delete()
    {
        AlbumFile::where('file_id', '=', $this->id)->delete();

        return parent::delete();
    }
}
