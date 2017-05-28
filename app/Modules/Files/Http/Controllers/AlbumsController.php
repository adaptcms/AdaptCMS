<?php

namespace App\Modules\Files\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modules\Files\Models\Album;

use Theme;
use Cache;

class AlbumsController extends Controller
{
    public function index()
    {
	    $albums = Album::paginate(10);

        $theme = Theme::uses(Cache::get('theme', 'default'))->layout('front');

        return $theme->scope('albums.index', [ 'albums' => $albums ])->render();
    }

    public function view($slug)
    {
	    $album = Album::where('slug', '=', $slug)->first();
      $files = $album->getFiles();

	    $theme = Theme::uses(Cache::get('theme', 'default'))->layout('front');

        return $theme->scope('albums.view', compact('album', 'files'))->render();
    }
}
