<?php

namespace App\Modules\Posts\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modules\Posts\Models\Field;
use App\Modules\Files\Models\File;
use App\Modules\Posts\Models\Category;

use Auth;
use Storage;
use Validator;

class FieldsController extends Controller
{
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

        $categories = Category::all();

        return view('posts::Admin/Fields/index', [
        	'items' => $items,
        	'field_types' => $model->field_types,
        	'categories' => $categories
        ]);
    }

    public function add(Request $request)
    {
        $model = new Field();

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

        return view('posts::Admin/Fields/add', [
        	'model' => $model,
        	'field_types' => $model->field_types,
        	'categories' => $model->getCategories(),
        	'files' => $files,
        	'images' => $images
        ]);
    }

    public function edit(Request $request, $id)
    {
        $model = Field::find($id);

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

        return view('posts::Admin/Fields/edit', [
        	'model' => $model,
        	'errors' => $errors,
        	'field_types' => $model->field_types,
        	'categories' => $model->getCategories(),
        	'files' => $files,
        	'images' => $images
        ]);
    }

    public function delete($id)
    {
        $model = Field::find($id)->delete();

        return redirect()->route('admin.fields.index')->with('success', 'Field has been saved');
    }

    public function order(Request $request)
    {
	      $items = Field::orderBy('ord', 'ASC')->get();

        if ($request->getMethod() == 'POST') {
						$items = json_decode($request->get('items'), true);

	        	foreach($items as $index => $id) {
			        	$item = Field::find($id);

			        	$item->ord = $index;

			        	$item->save();
	        	}

	        	return response()->json([
		        	   'status' => true
	        	]);
        }

        return view('posts::Admin/Fields/order', [ 'items' => $items ]);
    }

	public function simpleSave(Request $request)
	{
		$model = new Field;

		$response = $model->simpleSave($request->all());

		return response()->json($response);
	}
}
