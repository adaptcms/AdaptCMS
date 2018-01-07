<?php

namespace App\Modules\Files\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modules\Files\Models\Album;

class AlbumsController extends Controller
{
    /**
    * Index
    *
    * @return object
    */
    public function index()
    {
	    $albums = Album::paginate(10);

        return $this->theme->scope('albums.index', compact('albums'))->render();
    }

    /**
    * View
    *
    * @param string $slug
    *
    * @return object
    */
    public function view($slug)
    {
	    $album = Album::where('slug', '=', $slug)->first();

        if (empty($album)) {
            abort(404, 'Could not find album.');
        }

        $files = $album->getFiles();

        return $this->theme->scope('albums.view', compact('album', 'files'))->render();
    }
}
