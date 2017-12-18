<?php

namespace App\Modules\Posts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modules\Posts\Models\Page;
use App\Modules\Posts\Models\Post;

use Theme;
use Cache;
use Storage;

class PagesController extends Controller
{
    public function home()
    {
        $page = Page::where('slug', '=', 'home')->first();

        if (empty($page)) {
            abort(404, 'Could not find home page.');
        }

        $page->body = Storage::disk('themes')->get('default/views/pages/' . $page->slug . '.blade.php');

        $this->theme->set('meta_keywords', $page->meta_keywords);
        $this->theme->set('meta_description', $page->meta_description);
        $this->theme->setTitle('Home');

	    $post = new Post;
	    $posts = $post->getAll();

        return $this->theme->scope('pages.home', compact('page', 'posts'))->render();
    }

    public function view($slug)
    {
	    $page = Page::where('slug', '=', $slug)->first();

        if (empty($page)) {
            abort(404, 'Could not find page.');
        }

        $page->body = Storage::disk('themes')->get('default/views/pages/' . $page->slug . '.blade.php');

        $this->theme->set('meta_keywords', $page->meta_keywords);
        $this->theme->set('meta_description', $page->meta_description);
        $this->theme->setTitle($page->name);

        return $this->theme->scope('pages.' . $slug, compact('page'))->render();
    }
}
