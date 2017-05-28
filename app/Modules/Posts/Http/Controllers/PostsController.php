<?php

namespace App\Modules\Posts\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modules\Posts\Models\Post;

use Theme;
use Cache;

class PostsController extends Controller
{
 	public function view($slug)
 	{
	 	$post = new Post;
	 	$post = $post->getBySlug($slug);

	 	$theme = Theme::uses(Cache::get('theme', 'default'))->layout('front');

        $theme->set('meta_keywords', $post['post']->meta_keywords);
        $theme->set('meta_description', $post['post']->meta_description);
        $theme->setTitle($post['post']->name);

		return $theme->scope('posts.view', compact('post'))->render();
 	}
}
