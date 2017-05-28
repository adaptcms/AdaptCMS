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

class PagesController extends Controller
{
    public function home()
    {
        $theme = Theme::uses(Cache::get('theme', 'default'))->layout('front');

        try {
            $page = Page::where('slug', '=', 'home')->firstOrFail();

            $theme->set('meta_keywords', $page->meta_keywords);
            $theme->set('meta_description', $page->meta_description);
            $theme->setTitle('Home');
        } catch(ModelNotFoundException $e) {
            abort(404);
        }

	    $post = new Post;
	    $posts = $post->getAll();

        return $theme->scope('pages.home', compact('page', 'posts'))->render();
    }

    public function view($slug)
    {
	    try {
		    $page = Page::where('slug', '=', $slug)->firstOrFail();

		    $theme = Theme::uses(Cache::get('theme', 'default'))->layout('front');

            $theme->set('meta_keywords', $page->meta_keywords);
            $theme->set('meta_description', $page->meta_description);
            $theme->setTitle($page->name);

	        return $theme->scope('pages.' . $slug, compact('page'))->render();
	    } catch(ModelNotFoundException $e) {
		    abort(404);
	    }
    }
}
