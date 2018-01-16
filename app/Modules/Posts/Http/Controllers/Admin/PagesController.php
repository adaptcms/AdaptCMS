<?php

namespace App\Modules\Posts\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;
use App\Modules\Posts\Models\Page;

use Auth;
use Storage;
use Validator;

class PagesController extends Controller
{
    /**
    * Dashboard
    *
    * @return View
    */
    public function dashboard()
    {
        return view('posts::Admin/Pages/dashboard');
    }

    /**
    * Index
    *
    * @param Request $request
    *
    * @return View
    */
    public function index(Request $request)
    {
        $items = Page::orderBy('name', 'ASC');

        if ($request->get('status') != '') {
            $items->where('status', '=', $request->get('status'));
        }

        $items = $items->paginate(15);

        return view('posts::Admin/Pages/index', compact('items'));
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
        $model = new Page;

        if ($request->getMethod() == 'POST') {
            $this->validate($request, [
                'name' => 'required|unique:pages|max:255',
                'body' => 'required',
                'slug' => 'unique:pages'
            ]);

            $data = $request->all();

            $data['user_id'] = Auth::user()->id;

            $model->add($data);

            return redirect()->route('admin.pages.index')->with('success', 'Page has been saved');
        }

        return view('posts::Admin/Pages/add', compact('model'));
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
        $model = Page::find($id);

        if (empty($model)) {
            abort(404);
        }

        $errors = [];
        if ($request->getMethod() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    Rule::unique('pages')->ignore($model->id)
                ],
                'slug' => [
                    Rule::unique('pages')->ignore($model->id)
                ],
                'body' => 'required'
            ]);

            if (!$validator->fails()) {
                $data = $request->all();

                $data['user_id'] = Auth::user()->id;

                $model->edit($data);

                return redirect()->route('admin.pages.index')->with('success', 'Page has been saved');
            } else {
                $errors = $validator->errors()->getMessages();
            }
        }

        $model->body = Storage::disk('themes')->get('default/views/pages/' . $model->slug . '.blade.php');

        return view('posts::Admin/Pages/edit', compact('model', 'errors'));
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
        $model = Page::find($id);

        if (empty($model)) {
            abort(404);
        }

        $model->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page has been saved');
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
        $model = Page::find($id);

        if (empty($model)) {
            abort(404);
        }

        $model->status = !$model->status;

        $model->save();

        return redirect()->route('admin.pages.index')->with('success', 'Page has been saved');
    }

    /**
    * Order
    *
    * @param Request $request
    *
    * @return mixed
    */
    public function order(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $items = json_decode($request->get('items'), true);

            foreach($items as $index => $id) {
                $item = Page::find($id);

                $item->ord = $index;

                $item->save();
            }

            return responder()->success()->respond();
        }

        $items = Page::orderBy('ord', 'ASC')->get();

        return view('posts::Admin/Pages/order', compact('items'));
    }

    /**
    * Add
    *
    * @param Request $request
    *
    * @return string
    */
    public function simpleSave(Request $request)
    {
        $model = new Page;

        $response = $model->simpleSave($request->all());

        return responder()->success($response)->respond();
    }
}
