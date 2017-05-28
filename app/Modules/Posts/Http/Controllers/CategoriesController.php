<?php

namespace App\Modules\Posts\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modules\Posts\Models\Category;
use App\Modules\Posts\Models\Post;

use Theme;
use Cache;

class CategoriesController extends Controller
{
    public function view($slug)
    {
	    $category = Category::where('slug', '=', $slug)->first();

	    $post = new Post;
	    $posts = $post->getAllByCategoryId($category->id);

        $theme = Theme::uses(Cache::get('theme', 'default'))->layout('front');

        $theme->set('meta_keywords', $category->meta_keywords);
        $theme->set('meta_description', $category->meta_description);
        $theme->setTitle($category->name);

        return $theme->scope('categories.view', compact('posts', 'category'))->render();
    }
}
