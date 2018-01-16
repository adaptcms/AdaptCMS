<?php

namespace App\Modules\Posts\Listeners;

use GuzzleHttp\Client;

use App\Modules\Core\Events\InstallSeedEvent;
use App\Modules\Posts\Models\Category;
use App\Modules\Posts\Models\Field;
use App\Modules\Posts\Models\Page;
use App\Modules\Posts\Models\Tag;

use App\Modules\Posts\Models\Post;
use App\Modules\Posts\Models\PostData;
use App\Modules\Posts\Models\PostTag;

use Core;
use Storage;

class InstallSeedListener
{
    /**
     * Handle the event.
     *
     * @param  OrderShipped  $event
     * @return void
     */
    public function handle(InstallSeedEvent $event)
    {
        $version = Core::getVersion();

        // create categories
        $categories = [
            [
                'name' => 'Blogs',
                'user_id' => 1
            ]
        ];
        foreach($categories as $index => $category) {
            $model = new Category;

            $model->name = $category['name'];
            $model->user_id = $category['user_id'];
            $model->ord = $index;

            $model->save();
        }

        // create pages
        $pages = [
            [
                'name' => 'Home',
                'user_id' => 1
            ],
            [
                'name' => 'About',
                'user_id' => 1
            ]
        ];
        foreach($pages as $index => $page) {
            $model = new Page;

            $model->name = $page['name'];
            $model->body = Storage::disk('themes')->get('default/views/pages/' . $model->slug . '.blade.php');
            $model->user_id = $page['user_id'];
            $model->status = 1;
            $model->ord = $index;

            $model->save();
        }

        // create fields
        $fields = [
            [
                'name' => 'blog_content',
                'caption' => 'Blog Content',
                'user_id' => 1,
                'category_id' => 1,
                'field_type' => 'wysiwyg'
            ]
        ];
        foreach($fields as $index => $field) {
            $model = new Field;

            $model->name = $field['name'];
            $model->caption = $field['caption'];
            $model->user_id = $field['user_id'];
            $model->category_id = $field['category_id'];
            $model->field_type = $field['field_type'];
            $model->ord = $index;
            $model->settings = json_encode([]);

            $model->save();
        }

        // create tags
        $tags = [
            [
                'name' => 'AdaptCMS',
                'user_id' => 1
            ],
            [
                'name' => 'AdaptCMS ' . ucfirst($version),
                'user_id' => 1
            ]
        ];
        foreach($tags as $index => $tag) {
            $model = new Tag;

            $model->add([
                'name' => $tag['name'],
                'user_id' => $tag['user_id'],
                'meta_keywords' => '',
                'meta_description' => ''
            ]);
        }

        // create default post
        // as well as post tags and post data
        $client = new Client();

        $res = $client->request('GET', 'https://marketplace.adaptcms.com/adaptcms/post_' . $version . '.html', [ 'http_errors' => false ]);

        if ($res->getStatusCode() == 200) {
            $post_body = (string) $res->getBody();
        }

        if (empty($post_body)) {
            $post_body = '<p>We hope you enjoy the system.</p>';
        }

        $posts = [
            [
                'name' => 'Welcome to AdaptCMS ' . ucfirst($version) . '!',
                'user_id' => 1,
                'category_id' => 1,
                'post_values' => [
                    1 => $post_body
                ]
            ]
        ];
        foreach($posts as $post) {
            $model = new Post;

            // add all the tags to the post
            $tagsArray = [];
            foreach($tags as $tag) {
                $tagsArray[] = $tag['name'];
            }

            $model->add([
                'name' => $post['name'],
                'user_id' => $post['user_id'],
                'category_id' => $post['category_id'],
                'status' => 1,
                'meta_keywords' => '',
                'meta_description' => '',
                'tags' => implode(',', $tagsArray),
                'post_values' => $post['post_values']
            ]);
        }
    }
}
