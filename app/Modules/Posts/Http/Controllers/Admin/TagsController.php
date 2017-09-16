<?php

namespace App\Modules\Posts\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

use App\Modules\Posts\Models\Tag;

use Auth;
use Validator;

class TagsController extends Controller
{
    public function index()
    {
        $items = Tag::orderBy('name', 'ASC')->paginate(15);
        
        return view('posts::Admin/Tags/index', [  'items' => $items ]);
    }

    public function add(Request $request)
    {
        $model = new Tag();

        if ($request->getMethod() == 'POST') {
	        $this->validate($request, [
		        'name' => 'required|unique:tags|max:255'
		    ]);
		    
		    $data = $request->all();
		    
		    $data['user_id'] = Auth::user()->id;
		    
		    $model->add($data);

            return redirect()->route('admin.tags.index')->with('success', 'Tag has been saved');
        }

        return view('posts::Admin/Tags/add', [ 'model' => $model ]);
    }

    public function edit(Request $request, $id)
    {
        $model = Tag::find($id);

		$errors = [];
        if ($request->getMethod() == 'POST') {
	        $validator = Validator::make($request->all(), [
		        'name' => [
			      	'required',
			      	Rule::unique('tags')->ignore($model->id)  
		        ]
		    ]);
		    
		    if (!$validator->fails()) {
		    	$data = $request->all();
		    
			    $data['user_id'] = Auth::user()->id;
			    
			    $model->edit($data);
	
	            return redirect()->route('admin.tags.index')->with('success', 'Tag has been saved');
	        } else {
		        $errors = $validator->errors()->getMessages();
	        }
        }

        return view('posts::Admin/Tags/edit', [ 'model' => $model, 'errors' => $errors ]);
    }

    public function delete($id)
    {
        $model = Tag::find($id)->delete();

        return redirect()->route('admin.tags.index')->with('success', 'Tag has been saved');
    }
    
    public function simpleSave(Request $request)
	{
		$model = new Tag;
		
		$response = $model->simpleSave($request->all());
		
		return response()->json($response);
	}
}
