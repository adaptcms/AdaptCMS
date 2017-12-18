<?php

namespace App\Modules\Posts\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modules\Posts\Models\Category;
use App\Modules\Posts\Models\Post;

class CategoriesController extends Controller
{
    public function view($slug)
    {
	    $category = Category::where('slug', '=', $slug)->first();

        if (empty($category)) {
            abort(404, 'Could not find category.');
        }

	    $post = new Post;
	    $posts = $post->getAllByCategoryId($category->id);

        $this->theme->set('meta_keywords', $category->meta_keywords);
        $this->theme->set('meta_description', $category->meta_description);
        $this->theme->setTitle($category->name);

        return $this->theme->scope('categories.view', compact('posts', 'category'))->render();
    }
}
