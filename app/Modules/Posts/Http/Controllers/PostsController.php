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
 		$post = Post::where('slug', '=', $slug)->first();

 		if (empty($post)) {
 			abort(404, 'Cannot find post.');
 		}

 		$post = $post->getRelationshipData($post);

        $this->theme->set('meta_keywords', $post['post']->meta_keywords);
        $this->theme->set('meta_description', $post['post']->meta_description);
        $this->theme->setTitle($post['post']->name);

		return $this->theme->scope('posts.view', compact('post'))->render();
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
 		
 		// get home page for meta data
 		$page = Page::where('slug', '=', 'home')->first();
 		
 		// set meta data if possible
 		if (!empty($page)) {
 			$this->theme->set('meta_keywords', $page->meta_keywords);
 			$this->theme->set('meta_description', $page->meta_description);
 		}
 		
 		$time = strtotime($year . '-' . $month . '-30');
        
        $this->theme->setTitle('Posts for ' . date('F Y', $time));

		return $this->theme->scope('posts.archive', compact('posts', 'time'))->render();
 	}
}
