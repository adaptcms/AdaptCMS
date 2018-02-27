<?php

namespace App\Modules\Posts\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Posts\Models\Page;
use App\Modules\Posts\Models\Post;

use Cache;
use Storage;
use Theme;

class PagesController extends Controller
{
    /**
    * Home
    *
    * @return View
    */
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

    /**
    * View
    *
    * @param string $slug
    *
    * @return View
    */
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
