<?php

namespace App\Modules\Core\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public $validModels = [
        // posts
        'posts' => '\App\Modules\Posts\Models\Post',
        'pages' => '\App\Modules\Posts\Models\Page',
        'tags' => '\App\Modules\Posts\Models\Tag',
        'fields' => '\App\Modules\Posts\Models\Field',
        'categories' => '\App\Modules\Posts\Models\Category',
        'post_data' => '\App\Modules\Posts\Models\PostData',
        'field_types' => '\App\Modules\Posts\Models\FieldType',

        // files
        'albums' => '\App\Modules\Files\Models\Album',
        'files' => '\App\Modules\Files\Models\File',

        // users
        'users' => '\App\Modules\Users\Models\User',

        // core
        'settings' => '\App\Modules\Settings\Models\Setting',
        
        // themes
        'themes' => '\App\Modules\Themes\Models\Theme',
    ];

    /**
    * Index
    *
    * @param Request $request
    * @param string $module
    *
    * @return string
    */
    public function index(Request $request, $module)
    {
        $response = [];
        if (empty($this->validModels[$module])) {
            $response = [
                'status' => false,
                'message' => 'Invalid module of: ' . $module
            ];
        } else {
            $model = new $this->validModels[$module];

            if (method_exists($model, 'searchLogic')) {
                $results = $model->searchLogic($request->all(), true);
            } else {
                $results =[];
            }

            $response = [
                'status' => true,
                'results' => $results
            ];
        }

        return response()->json($response);
    }

    /**
    * Add
    *
    * @param Request $request
    * @param string $module
    *
    * @return string
    */
    public function add(Request $request, $module)
    {
        $response = [];
        if (empty($this->validModels[$module])) {
            $response = [
                'status' => false,
                'message' => 'Invalid module of: ' . $module
            ];
        } else {
            $model = new $this->validModels[$module];

            $model->add($request->all());

            $response = [
                'status' => true,
                'model' => $model
            ];
        }

        return response()->json($response);
    }

    /**
    * Edit
    *
    * @param Request $request
    * @param string $module
    * @param integer $id
    *
    * @return string
    */
    public function edit(Request $request, $module, $id)
    {
        $response = [];
        if (empty($this->validModels[$module])) {
            $response = [
                'status' => false,
                'message' => 'Invalid module of: ' . $module
            ];
        } else {
            $model = $this->validModels[$module]::find($id);

            $model->edit($request->all());

            $response = [
                'status' => true,
                'id' => $id,
                'model' => $model
            ];
        }

        return response()->json($response);
    }

    /**
    * Delete
    *
    * @param string $module
    * @param integer $id
    *
    * @return string
    */
    public function delete($module, $id)
    {
        $response = [];
        if (empty($this->validModels[$module])) {
            $response = [
                'status' => false,
                'message' => 'Invalid module of: ' . $module
            ];
        } else {
            $model = $this->validModels[$module]::find($id);

            $model->delete();

            $response = [
                'status' => true,
                'id' => $id,
                'model' => $model
            ];
        }

        return response()->json($response);
    }
}
