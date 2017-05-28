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

    public function albumFiles()
    {
        return $this->hasMany('App\Modules\Files\Models\AlbumFile');
    }

    public function user()
    {
	    return $this->belongsTo('App\Modules\Users\Models\User');
    }

    public function getRelatedVal()
    {
	    $val = [];
	    foreach($this->albumFiles as $row) {
		    $val[] = $row->album_id;
	    }

	    return $val;
    }

    public static function getFiles()
    {
	    return self::pluck('filename', 'id');
    }

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

    public function simpleSave($data)
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

    public function searchLogic($searchData)
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

    public function add($postArray, $file, $albums)
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

    public function edit($postArray, $file, $albums)
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

    public function delete()
    {
	    AlbumFile::where('file_id', '=', $this->id)->delete();

	    return parent::delete();
    }
}
