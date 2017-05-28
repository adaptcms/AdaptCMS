<?php

namespace App\Modules\Posts\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

use App\Modules\Posts\Models\Category;

use Auth;
use Validator;

class CategoriesEngineController extends Controller
{
    public function index()
    {
        $items = Category::orderBy('ord', 'ASC')->paginate(15);

        return view('posts::CategoriesEngine/index', [  'items' => $items ]);
    }

    public function add(Request $request)
    {
        $model = new Category();

        if ($request->getMethod() == 'POST') {
	        $this->validate($request, [
		        'name' => 'required|unique:pages|max:255'
		    ]);

		    $data = $request->all();

		    $data['user_id'] = Auth::user()->id;

		    $model->add($data);

            return redirect()->route('admin.categories.index')->with('success', 'Category has been saved');
        }

        return view('posts::CategoriesEngine/add', [ 'model' => $model ]);
    }

    public function edit(Request $request, $id)
    {
        $model = Category::find($id);

		$errors = [];
        if ($request->getMethod() == 'POST') {
	        $validator = Validator::make($request->all(), [
		        'name' => [
			      	'required',
			      	Rule::unique('categories')->ignore($model->id)
		        ]
		    ]);

		    if (!$validator->fails()) {
			    $data = $request->all();

			    $data['user_id'] = Auth::user()->id;

			    $model->edit($data);

	            return redirect()->route('admin.categories.index')->with('success', 'Category has been saved');
	        } else {
		        $errors = $validator->errors()->getMessages();
	        }
        }

        return view('posts::CategoriesEngine/edit', [ 'model' => $model, 'errors' => $errors ]);
    }

    public function delete($id)
    {
        $model = Category::find($id)->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category has been saved');
    }

    public function order(Request $request)
    {
        $items = Category::orderBy('ord', 'ASC')->get();

        if ($request->getMethod() == 'POST') {
						$items = json_decode($request->get('items'), true);

	        	foreach($items as $index => $id) {
			        	$item = Category::find($id);

			        	$item->ord = $index;

			        	$item->save();
	        	}

	        	return response()->json([
		        	'status' => true
	        	]);
        }

        return view('posts::CategoriesEngine/order', [ 'items' => $items ]);
    }

    public function simpleSave(Request $request)
	{
		$model = new Category;

		$response = $model->simpleSave($request->all());

		return response()->json($response);
	}
}
