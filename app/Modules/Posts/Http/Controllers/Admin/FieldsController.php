<?php

namespace App\Modules\Posts\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;
use App\Modules\Files\Models\File;
use App\Modules\Posts\Models\Field;
use App\Modules\Posts\Models\Category;

use Auth;
use Storage;
use Validator;

class FieldsController extends Controller
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
        $items = Field::orderBy('name', 'ASC');

        if ($request->get('category_id') != '') {
            $items->where('category_id', '=', $request->get('category_id'));
        }

        if ($request->get('field_type') != '') {
            $items->where('field_type', '=', $request->get('field_type'));
        }

        $items = $items->paginate(15);

        $model = new Field;

        $field_types = $model->field_types;
        $categories = Category::all();

        return view('posts::Admin/Fields/index', compact('items', 'field_types', 'categories'));
    }

    /**
    * Add
    *
    * @param Request $request
    *
    * @return mixed
    */
    public function add(Request $request)
    {
        $model = new Field;

        if ($request->getMethod() == 'POST') {
            $this->validate($request, [
                'name' => 'required|unique:fields|max:255',
                'caption' => 'required',
                'field_type' => 'required',
                'category_id' => 'required'
            ]);

            $data = $request->all();

            $data['user_id'] = Auth::user()->id;

            $model->add($data);

            return redirect()->route('admin.fields.index')->with('success', 'Field has been saved');
        }

        $files = File::getFiles();
        $images = File::getImages();
        $field_types = $model->field_types;
        $categories = $model->getCategories();

        return view('posts::Admin/Fields/add', compact(
            'model',
            'field_types',
            'categories',
            'files',
            'images'
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
        $model = Field::find($id);

        if (empty($model)) {
            abort(404);
        }

        $errors = [];
        if ($request->getMethod() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    Rule::unique('posts')->ignore($model->id),
                    'caption' => 'required',
                    'field_type' => 'required',
                    'category_id' => 'required'
                ]
            ]);

            if (!$validator->fails()) {
                $data = $request->all();

                $data['user_id'] = Auth::user()->id;

                $model->edit($data);

                return redirect()->route('admin.fields.index')->with('success', 'Field has been saved');
            } else {
                $errors = $validator->errors()->getMessages();
            }
        }

        $files = File::getFiles();
        $images = File::getImages();
        $field_types = $model->field_types;
        $categories = $model->getCategories();

        return view('posts::Admin/Fields/edit', compact(
            'model',
            'errors',
            'field_types',
            'categories',
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
        $model = Field::find($id);

        if (empty($model)) {
            abort(404);
        }

        $model->delete();

        return redirect()->route('admin.fields.index')->with('success', 'Field has been saved');
    }

    /**
    * Order
    *
    * @param Request $request
    *
    * @return View
    */
    public function order(Request $request)
    {
        $items = Field::orderBy('ord', 'ASC')->get();

        if ($request->getMethod() == 'POST') {
            $items = json_decode($request->get('items'), true);

            Field::setNewOrder($items);

            return response()->json([
               'status' => true
            ]);
        }

        return view('posts::Admin/Fields/order', compact('items'));
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
        $model = new Field;

        $response = $model->simpleSave($request->all());

        return response()->json($response);
    }
}
