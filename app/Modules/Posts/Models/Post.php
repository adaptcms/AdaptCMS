<?php

namespace App\Modules\Posts\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

use App\Modules\Posts\Models\PostRevision;
use App\Modules\Posts\Models\PostData;
use App\Modules\Posts\Models\PostTag;
use App\Modules\Posts\Models\PostRelated;
use App\Modules\Posts\Models\Field;
use App\Modules\Posts\Models\Category;
use App\Modules\Posts\Models\Tag;

class Post extends Model
{
    use Searchable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'status',
        'meta_keywords',
        'meta_description'
    ];

    public function postData()
    {
        return $this->hasMany('App\Modules\Posts\Models\PostData');
    }

    public function postTags()
    {
        return $this->hasMany('App\Modules\Posts\Models\PostTag');
    }

    public function postRevisions()
    {
        return $this->hasMany('App\Modules\Posts\Models\PostRevision');
    }

    public function relatedPosts()
    {
        return $this->hasMany('App\Modules\Posts\Models\PostRelated', 'from_post_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Modules\Posts\Models\Category');
    }

    public function user()
    {
        return $this->belongsTo('App\Modules\Users\Models\User');
    }

    public function getStringTags()
    {
        $postTags = $this->postTags;
        $text = [];

        foreach ($postTags as $tag) {
            $text[] = $tag->tag->name;
        }

        return implode(',', $text);
    }

    public function getRelatedVal()
    {
        $val = [];
        foreach ($this->relatedPosts as $row) {
            $val[] = $row->to_post_id;
        }

        return $val;
    }

    public function searchLogic($searchData, $admin = false)
    {
        if (!empty($searchData['keyword'])) {
            $results = Post::search($searchData['keyword'])->get();
        } else {
            $results = [];
        }

        foreach ($results as $key => $row) {
            if ($admin) {
                $results[$key]->url = route('admin.posts.edit', [ 'id' => $row->id ]);
            } else {
                $results[$key]->url = route('posts.view', [ 'slug' => $row->slug ]);
            }
        }

        return $results;
    }

    public function simpleSave($data)
    {
        if (!empty($data['many'])) {
            $data['ids'] = json_decode($data['ids'], true);

            switch ($data['type']) {
                case 'delete':
                    Post::whereIn('id', $data['ids'])->delete();
                break;

                case 'toggle-statuses':
                    $active_posts = Post::whereIn('id', $data['ids'])->where('status', '=', 1)->get();
                    $pending_posts = Post::whereIn('id', $data['ids'])->where('status', '=', 0)->get();

                    foreach ($active_posts as $post) {
                        $post->status = 0;

                        $post->save();
                    }

                    foreach ($pending_posts as $post) {
                        $post->status = 1;

                        $post->save();
                    }
                break;
            }
        }

        return [
            'status' => true,
            'ids' => $data['ids']
        ];
    }

    public function add($postArray)
    {
        // save post
        $this->name = $postArray['name'];
        $this->slug = $postArray['slug'];
        $this->status = $postArray['status'];
        $this->user_id = $postArray['user_id'];
        $this->category_id = $postArray['category_id'];
        $this->settings = empty($postArray['settings']) ? '[]' : json_encode($postArray['settings']);
        $this->meta_keywords = $postArray['meta_keywords'];
        $this->meta_description = $postArray['meta_description'];

        $this->save();

        // save related posts
        $related_posts_array = [];
        if (!empty($postArray['related_posts'])) {
            $related_posts = $postArray['related_posts'];

            if (!empty($related_posts)) {
                foreach ($related_posts as $row) {
                    $related = new PostRelated;

                    $related->from_post_id = $this->id;
                    $related->to_post_id = $row;

                    $related->save();

                    $related_posts_array[] = $related;
                }
            }
        }

        // save post data
        $post_data = [];
        if (!empty($postArray['post_values'])) {
            foreach ($postArray['post_values'] as $key => $val) {
                $post_data[] = new PostData([
                    'field_id' => $key,
                    'user_id' => $postArray['user_id'],
                    'post_id' => $this->id,
                    'body' => $val
                ]);
            }

            $this->postData()->saveMany($post_data);
        }

        // post revision
        $body = [
            'post' => $this,
            'postData'  => $post_data,
            'tags' => !empty($postArray['tags']) ? $postArray['tags'] : [],
            'relatedPosts' => $related_posts_array
        ];

        $revision = new PostRevision;

        $revision->post_id = $this->id;
        $revision->user_id = $postArray['user_id'];
        $revision->status = 1;
        $revision->body = json_encode($body);

        $revision->save();

        // save post tags
        if (!empty($postArray['tags'])) {
            $tags = explode(',', $postArray['tags']);

            foreach ($tags as $tag) {
                $slug = str_slug($tag, '-');

                $tagFetch = Tag::where('slug', '=', $slug)->first();

                // tag exists
                if (!$tagFetch) {
                    // tag doesn't exist, create it and attach to post
                    $newTag = new Tag;

                    $newTag->name = $tag;
                    $newTag->slug = $slug;
                    $newTag->user_id = $postArray['user_id'];

                    $newTag->save();
                }

                $pTag = new PostTag;

                $pTag->post_id = $this->id;
                $pTag->tag_id = !empty($newTag) ? $newTag->id : $tagFetch->id;

                $pTag->save();
            }
        }

        return $this;
    }

    public function edit($postArray)
    {
        // save post
        $this->name = $postArray['name'];
        $this->slug = $postArray['slug'];
        $this->status = $postArray['status'];
        $this->settings = empty($postArray['settings']) ? '[]' : json_encode($postArray['settings']);
        $this->user_id = $postArray['user_id'];
        $this->meta_keywords = $postArray['meta_keywords'];
        $this->meta_description = $postArray['meta_description'];

        $this->save();

        // delete posts related
        PostRelated::where('from_post_id', '=', $this->id)->delete();

        // and then rebuild
        $related_posts_array = [];
        if (!empty($postArray['related_posts'])) {
            $related_posts = $postArray['related_posts'];

            if (!empty($related_posts)) {
                foreach ($related_posts as $row) {
                    $related = new PostRelated;

                    $related->from_post_id = $this->id;
                    $related->to_post_id = $row;

                    $related->save();

                    $related_posts_array[] = $related;
                }
            }
        }

        // // save post data
        $existingPostData = [];
        foreach ($this->postData as $row) {
            $existingPostData[$row->field_id] = $row;
        }

        $post_data = [];
        if (!empty($postArray['post_values'])) {
            foreach ($postArray['post_values'] as $field_id => $val) {
                if (!empty($existingPostData[$field_id])) {
                    $existingPostData[$field_id]->body = $val;

                    $post_data[] = $existingPostData[$field_id];
                } else {
                    $post_data[] = new PostData([
                        'field_id' => $field_id,
                        'user_id' => $postArray['user_id'],
                        'post_id' => $this->id,
                        'body' => $val
                    ]);
                }
            }

            $this->postData()->saveMany($post_data);
        }

        // post revision
        $body = [
            'post' => $this,
            'postData'  => $post_data,
            'tags' => !empty($postArray['tags']) ? $postArray['tags'] : [],
            'relatedPosts' => $related_posts_array
        ];

        $revision = new PostRevision;

        $revision->post_id = $this->id;
        $revision->user_id = $postArray['user_id'];
        $revision->status = 1;
        $revision->body = json_encode($body);

        $revision->save();

        // delete existing post tags
        PostTag::where('post_id', '=', $this->id)->delete();

        // then, rebuild and save post tags
        if (!empty($postArray['tags'])) {
            $tags = explode(',', $postArray['tags']);

            foreach ($tags as $tag) {
                $slug = str_slug($tag, '-');

                $tagFetch = Tag::where('slug', '=', $slug)->first();

                // tag exists
                if ($tagFetch) {
                    $pTag = new PostTag;

                    $pTag->post_id = $this->id;
                    $pTag->tag_id = $tagFetch->id;

                    $pTag->save();
                } else {
                    // tag doesn't exist, create it and attach to post
                    $newTag = new Tag;

                    $newTag->name = $tag;
                    $newTag->slug = $slug;
                    $newTag->user_id = $postArray['user_id'];

                    $newTag->save();

                    $pTag = new PostTag;

                    $pTag->post_id = $this->id;
                    $pTag->tag_id = $newTag->id;

                    $pTag->save();
                }
            }
        }

        return $this;
    }

    public function delete()
    {
        foreach ($this->postData as $row) {
            $row->delete();
        }

        return parent::delete();
    }

    public function getPostData($postData)
    {

				// if single, convert to multiple
        $single = false;
        if ((is_object($postData) && !strstr(get_class($postData), 'Pagination')) || !is_object($postData)) {
            if (!is_array($postData)) {
    						$single = true;
    						$postData = [ $postData ];
    				}
        }

        // first, build an array of posts containing post data values
        // also, grab the category ID's for later purposes
        $category_ids = [];
				$posts = [];
        foreach ($postData as $post) {
            $posts[] = [
                'post' => $post,
                'post_data' => [],
                'tags' => [],
                'related_posts' => []
            ];

            if (!in_array($post->category_id, $category_ids)) {
                $category_ids[] = $post->category_id;
            }

            $post_data = $post->postData;
            if (!empty($post_data)) {
                foreach ($post_data as $row) {
                    $posts[count($posts) - 1]['post_data'][] = $row;
                }
            }
        }

        // then get all the field data for the IDs supplied
        // and array key them by their ID for the last step
        $tmp_fields = Field::wherein('category_id', $category_ids)->get();

        $fields = [];
        foreach ($tmp_fields as $field) {
            $fields[$field->id] = $field;
        }

        // last step for the post data, update the posts array with keyed indexes
        // of the field data. ex: $post['post_data']['overall_score']
        foreach ($posts as $key => $row) {
            $post_data = $row['post_data'];

            $posts[$key]['post_data'] = [];

            foreach ($post_data as $val) {
                if (!isset($fields[$val->field_id])) {
                    dd($tmp_fields, $val->id);
                }
                $field_slug = $fields[$val->field_id]->slug;

                $posts[$key]['post_data'][$field_slug] = $val->body;
            }

						foreach($fields as $field) {
								if (!isset($posts[$key]['post_data'][$field->slug])) {
										$posts[$key]['post_data'][$field->slug] = '';
								}
						}
        }

        return $single ? $posts[0] : $posts;
    }

    public function getRelationshipData($posts)
    {
        // get post data first
        $data = $this->getPostData($posts);

        if (!empty($data['post'])) {
            $single = true;
            $data = [ $data ];
        } else {
            $single = false;
        }

        // now onto tags
        foreach ($data as $key => $row) {
            foreach ($row['post']->postTags as $postTag) {
                $data[$key]['tags'][] = $postTag->tag;
            }
        }

        // lastly, related articles
        foreach ($data as $key => $row) {
            foreach ($row['post']->relatedPosts as $relatedPost) {
                $data[$key]['related_posts'][] = $this->getPostData($relatedPost->toPost);
            }
        }

        return $single ? $data[0] : $data;
    }

    public function getAllByCategoryId($category_id)
    {
        $posts = Post::where('category_id', '=', $category_id)->where('status', '=', 1)->paginate(10);
        $posts_with_data = $this->getRelationshipData($posts);

        return compact('posts', 'posts_with_data');
    }

    public function getAll()
    {
        $posts = Post::where('status', '=', 1)->paginate(10);
        $posts_with_data = $this->getRelationshipData($posts);

        return compact('posts', 'posts_with_data');
    }

    public function getAllByTagSlug($tag_slug)
    {
        // first, translate the tag slug to a tag object
        $tag = Tag::where('slug', '=', $tag_slug)->first();

        // then get all the post IDs for that tag
        $post_ids = PostTag::where('tag_id', '=', $tag->id)->pluck('post_id');

        // get the paginated version
        $posts = Post::whereIn('id', $post_ids)->where('status', '=', 1)->paginate(10);

        // and the version with associated relationship data
        $posts_with_data = $this->getRelationshipData($posts);

        return compact('posts', 'posts_with_data', 'tag');
    }

    public function getBySlug($slug)
    {
        // get the initial post object
        $post = Post::where('slug', '=', $slug)->first();

        // and then transform to one with associated relationship data
        $post = $this->getRelationshipData($post);

        return $post;
    }

    public function getFieldValue($post_data, $field)
    {
        return !empty($post_data[$field]) ? $post_data[$field] : '';
    }
}
