<?php

namespace App\Modules\Files\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modules\Files\Models\File;
use App\Modules\Files\Models\Album;
use App\Modules\Files\Models\AlbumFile;

use Auth;
use Storage;

class FilesController extends Controller
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
	 	$items = File::orderBy('filename', 'ASC');

	 	if ($request->get('file_type')) {
		 	$items->where('file_type', '=', $request->get('file_type'));
	 	}

	 	$items = $items->paginate(15);

	 	$file_types = File::getFileTypes();

        return view('files::Admin/Files/index', compact('items', 'file_types'));
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
        $model = new File();
        $albums = Album::pluck('name', 'id');

        if ($request->getMethod() == 'POST') {
	        $this->validate($request, [
		        'file' => 'required'
		    ]);

		    if ($request->file('file')->isValid()) {
			    $data = $request->all();

			    $data['user_id'] = Auth::user()->id;

			    $model->add($data, $request->file, $request->get('albums'));

	            return redirect()->route('admin.files.index')->with('success', 'File has been saved');
	        } else {
		        $request->session()->flash('status', 'Could not upload file, please try again.');
	        }
        }


        return view('files::Admin/Files/add', compact('model', 'albums'));
    }

    /**
    * Add
    *
    * @param Request $request
    * @param integer $id
    *
    * @return mixed
    */
    public function edit(Request $request, $id)
    {
        $model = File::find($id);
        $albums = Album::pluck('name', 'id');

        if ($request->getMethod() == 'POST') {
	        $data = $request->all();

		    $data['validFile'] = ($request->file('file') && $request->file('file')->isValid());
	        $data['user_id'] = Auth::user()->id;

			$model->edit($data, $request->file, $request->get('albums'));

	        return redirect()->route('admin.files.index')->with('success', 'File has been saved');
        }

        return view('files::Admin/Files/edit', compact('model', 'albums'));
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
        $model = File::find($id);

        if (empty($model)) {
            abort(404);
        }

        $model->delete();

        return redirect()->route('admin.files.index')->with('success', 'File has been saved');
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
        $model = new File;

        $response = $model->simpleSave($request->all());

        return response()->json($response);
    }
}
