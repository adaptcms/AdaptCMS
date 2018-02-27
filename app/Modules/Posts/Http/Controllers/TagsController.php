<?php

namespace App\Modules\Posts\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Posts\Models\Post;
use App\Modules\Posts\Models\Tag;

use Cache;
use Theme;

class TagsController extends Controller
{
    /**
    * View
    *
    * @param string $slug
    *
    * @return View
    */
    public function view($slug)
    {
        $post = new Post;
        $posts = $post->getAllByTagSlug($slug);
        $tag = $posts['tag'];

        $this->theme->set('meta_keywords', $tag->meta_keywords);
        $this->theme->set('meta_description', $tag->meta_description);
        $this->theme->setTitle($tag->name);

        return $this->theme->scope('tags.view', compact('posts', 'tag'))->render();
    }
}
