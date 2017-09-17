<?php

namespace App\Modules\Posts\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modules\Posts\Models\Page;
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
 	
 	/**
 	* Archive
 	* Posts for archive period
 	*
 	* @return Response
 	*/
 	public function archive($year, $month)
 	{
 		// get posts for period
 		$posts = [];
 		
 		$paginated = Post::where('created_at', 'LIKE', '%' . $year . '-' . $month . '%')->paginate(15);
		$posts['paginated'] = $paginated;
 		
 		// get posts relationship data
 		$model = new Post;
 		
 		$posts['posts_with_data'] = $model->getRelationshipData($paginated);
 		
 		// get theme
 		$theme = Theme::uses(Cache::get('theme', 'default'))->layout('front');
 		
 		// get home page for meta data
 		$page = Page::where('slug', '=', 'home')->first();
 		
 		// set meta data if possible
 		if (!empty($page)) {
 			$theme->set('meta_keywords', $page->meta_keywords);
 			$theme->set('meta_description', $page->meta_description);
 		}
 		
 		$time = strtotime($year . '-' . $month . '-30');
        
        $theme->setTitle('Posts for ' . date('F Y', $time));

		return $theme->scope('posts.archive', compact('posts', 'time'))->render();
 	}
}
