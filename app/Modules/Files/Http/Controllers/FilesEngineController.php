<?php

namespace App\Modules\Files\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modules\Files\Models\File;
use App\Modules\Files\Models\Album;
use App\Modules\Files\Models\AlbumFile;

use Auth;
use Storage;

class FilesEngineController extends Controller
{
    public function index(Request $request)
    {
	 	$items = File::orderBy('filename', 'ASC');

	 	if ($request->get('file_type')) {
		 	$items->where('file_type', '=', $request->get('file_type'));
	 	}

	 	$items = $items->paginate(15);

	 	$file_types_tmp = File::pluck('file_type')->groupBy('file_type');
        $file_types = [];
        foreach($file_types_tmp as $file) {
	        foreach($file as $row) {
		        $file_types[$row] = $row;
	        }
        }

        asort($file_types);

        return view('files::FilesEngine/index', [
        	'items' => $items,
        	'file_types' => $file_types
        ]);
    }

    public function add(Request $request)
    {
        $model = new File();

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

        return view('files::FilesEngine/add', [
        	'model' => $model,
        	'albums' => Album::pluck('name', 'id')
        ]);
    }

    public function edit(Request $request, $id)
    {
        $model = File::find($id);

        if ($request->getMethod() == 'POST') {
	        $data = $request->all();

	        $data['user_id'] = Auth::user()->id;

		    $data['validFile'] = $request->file('file') && $request->file('file')->isValid();
	        $data['user_id'] = Auth::user()->id;

			$model->edit($data, $request->file, $request->get('albums'));

	        return redirect()->route('admin.files.index')->with('success', 'File has been saved');
        }

        return view('files::FilesEngine/edit', [
        	'model' => $model,
        	'albums' => Album::pluck('name', 'id')
        ]);
    }

    public function delete($id)
    {
        $model = File::find($id)->delete();

        return redirect()->route('admin.files.index')->with('success', 'File has been saved');
    }

    public function simpleSave(Request $request)
    {
        $model = new File;

        $response = $model->simpleSave($request->all());

        return response()->json($response);
    }
}
