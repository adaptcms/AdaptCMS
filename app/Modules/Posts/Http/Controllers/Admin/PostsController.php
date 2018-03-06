<?php

namespace App\Modules\Posts\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;
use App\Modules\Posts\Models\Category;
use App\Modules\Posts\Models\Field;
use App\Modules\Files\Models\File;
use App\Modules\Posts\Models\Post;
use App\Modules\Posts\Models\PostData;
use App\Modules\Posts\Models\PostRelated;
use App\Modules\Posts\Models\PostRevision;
use App\Modules\Posts\Models\PostTag;
use App\Modules\Posts\Models\Tag;

use Auth;
use Validator;

class PostsController extends Controller
{
    /**
    * Index
    *
    * @param Request $request
    *
    * @return View
    */
    public function index(Request $request)
    {
        $items = Post::filter($request->all())->filterPaginate(15);
        $categories = Category::all();

        return view('posts::Admin/Posts/index', compact('items', 'categories'));
    }

    /**
    * Add
    *
    * @param Request $request
    * @param integer $category_id
    *
    * @return mixed
    */
    public function add(Request $request, $category_id)
    {
        $model = new Post;

        if ($request->getMethod() == 'POST') {
            $this->validate($request, [
                'name' => 'required|max:255'
            ]);

            $data = $request->all();

            $data['category_id'] = $category_id;
            $data['user_id'] = Auth::user()->id;

            $model->add($data);

            return redirect()->route('admin.posts.index')->with('success', 'Post has been saved');
        }

        $images = File::getImages();
        $files = File::getFiles();

        $fields = Field::where('category_id', '=', $category_id)->get();
        $posts = Post::pluck('name', 'id');

        return view('posts::Admin/Posts/add', compact(
            'model',
            'fields',
            'posts',
            'images',
            'files'
        ));
    }

    /**
    * Edit
    *
    * @param Request $request
    * @param integer $id
    *
    * @return mixed
    */
    public function edit(Request $request, $id)
    {
        $model = Post::find($id);

        $errors = [];
        if ($request->getMethod() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'slug' => [
                      'required',
                      Rule::unique('posts')->ignore($model->id)
                ]
            ]);

            if (!$validator->fails()) {
                $data = $request->all();

                $data['user_id'] = Auth::user()->id;

                $model->edit($data);

                return redirect()->route('admin.posts.index')->with('success', 'Post has been saved');
            } else {
                $errors = $validator->errors()->getMessages();
            }
        }

        $post_data = $model->getPostData($model);

        $images = File::getImages();
        $files = File::getFiles();

        $fields = Field::where('category_id', '=', $model->category_id)->get();
        $posts = Post::where('id', '!=', $id)->pluck('name', 'id');

        return view('posts::Admin/Posts/edit', compact(
            'model',
            'fields',
            'posts',
            'post_data',
            'errors',
            'images',
            'files',
            'images'
        ));
    }

    /**
    * Delete
    *
    * @param integer $id
    *
    * @return Redirect
    */
    public function delete($id)
    {
        $model = Post::find($id);

        if (empty($model)) {
            abort(404);
        }

        $model->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Post has been saved');
    }

    /**
    * Status
    *
    * @param integer $id
    *
    * @return Redirect
    */
    public function status($id)
    {
        $model = Post::find($id);

        if (empty($model)) {
            abort(404);
        }

        $model->status = !$model->status;

        $model->save();

        return redirect()->route('admin.posts.index')->with('success', 'Post has been saved');
    }

    /**
    * Restore
    *
    * @param integer $id
    *
    * @return Redirect
    */
    public function restore($id)
    {
        // find revision and post, json_decode body data
        $revision = PostRevision::find($id);
        $post = Post::find($revision->post_id);

        $revision = json_decode($revision->body, true);

        // restore post
        $post->fill($revision['post']);

        $post->save();

        // save related posts
        $related_posts = $revision['relatedPosts'];

        PostRelated::where('from_post_id', '=', $post->id)->delete();

        $related_posts_array = [];
        if (!empty($related_posts)) {
            foreach ($related_posts as $row) {
                $related = new PostRelated;

                $related->from_post_id = $post->id;
                $related->to_post_id = $row['to_post_id'];

                $related->save();

                $related_posts_array[] = $related;
            }
        }

        // restore post data
        foreach ($revision['postData'] as $key => $row) {
            $postData = PostData::find($row['id']);

            $postData->body = $row['body'];

            $postData->save();
        }

        // save post tags
        $tags = explode(',', $revision['tags']);

        // delete existing post tags
        PostTag::where('post_id', '=', $post->id)->delete();

        foreach ($tags as $tag) {
            $tagFetch = Tag::where('name', '=', $tag)->first();

            $pTag = new PostTag;

            $pTag->post_id = $post->id;
            $pTag->tag_id = $tagFetch->id;

            $pTag->save();
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post revision has been restored');
    }

    /**
    * Simple Save
    *
    * @param Request $request
    *
    * @return string
    */
    public function simpleSave(Request $request)
    {
        $post = new Post;

        $response = $post->simpleSave($request->all());

        return response()->json($response);
    }
}
