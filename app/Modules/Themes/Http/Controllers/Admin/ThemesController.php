<?php

namespace App\Modules\Themes\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;
use App\Modules\Themes\Models\Theme;

use Auth;
use Cache;
use Storage;
use Validator;

class ThemesController extends Controller
{
    /**
    * Index
    *
    * @return View
    */
    public function index()
    {
        $items = Theme::orderBy('name', 'ASC')->get();

        // build array of themes by slug
        $items_array = [];
        foreach($items as $item) {
            $items_array[$item->slug] = $item;
        }

        // loop through directories and create base model
        // to display themes not in DB
        $dirs = Storage::disk('themes')->directories();
        foreach($dirs as $dir) {
            if (empty($items_array[$dir])) {
                $theme = new Theme;

                $theme->name = str_replace('-', '', ucfirst($dir));
                $theme->slug = $dir;
                $theme->status = 0;

                $items[] = $theme;
            }
        }

        return view('themes::Admin/Themes/index', compact('items'));
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
        $model = new Theme;

        if ($request->getMethod() == 'POST') {
            $this->validate($request, [
                'name' => 'required|unique:themes|max:255'
            ]);

            $data = $request->all();

            $data['user_id'] = Auth::user()->id;

            $model->add($data);

            return redirect()->route('admin.themes.index')->with('success', 'Theme has been saved');
        }

        return view('themes::Admin/Themes/add', compact('model'));
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
        $model = Theme::find($id);

        $errors = [];
        if ($request->getMethod() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    Rule::unique('themes')->ignore($model->id)
                ]
            ]);

            if (!$validator->fails()) {
                $data = $request->all();

                $data['user_id'] = Auth::user()->id;

                $model->edit($data);
            } else {
                $errors = $validator->errors()->getMessages();
            }

            return redirect()->route('admin.themes.index')->with('success', 'Theme has been saved');
        }

        return view('themes::Admin/Themes/edit', compact('model', 'errors'));
    }

    /**
    * Edit Templates
    *
    * @param integer $id
    *
    * @return View
    */
    public function edit_templates($id)
    {
        $theme = Theme::find($id);

        if (empty($theme)) {
            abort(404);
        }

        $files = Storage::disk('themes')->allFiles($theme->slug);

        return view('themes::Admin/Themes/edit_templates', compact('theme', 'files'));
    }

    /**
    * Edit Template
    *
    * @param Request $request
    * @param integer $id
    * @param string $path
    *
    * @return mixed
    */
    public function edit_template(Request $request, $id, $path)
    {
        $theme = Theme::find($id);

        if (empty($theme)) {
            abort(404);
        }

        $path = base64_decode($path);

        if ($request->getMethod() == 'POST' && $request->get('body')) {
            $body = trim($request->get('body'));
            $body = html_entity_decode($body);
            $body = str_replace("\r\n", PHP_EOL, $body);

            Storage::disk('themes')->put($path, $body);

            return redirect()->route('admin.themes.edit_templates', compact('id'))
                ->with('success', 'Template has been saved');
        }

        return view('themes::Admin/Themes/edit_template', compact('theme', 'path'));
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
        $model = Theme::find($id);

        if (empty($model)) {
            abort(404);
        }

        $model->delete();

        return redirect()->route('admin.themes.index')->with('success', 'Theme has been saved');
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
        $model = Theme::find($id);

        if (empty($model)) {
            abort(404);
        }

        $model->status = !$model->status;

        $model->save();

        if ($model->status) {
            $themes = Theme::where('status', '=', 1)->where('id', '!=', $model->id)->get();
            foreach($themes as $theme) {
                $theme->status = 0;

                $theme->save();
            }

            Cache::forever('theme', $model->slug);
        }

        return redirect()->route('admin.themes.index')->with('success', 'Theme has been made active.');
    }

    /**
    * Activate
    *
    * @param string $slug
    *
    * @return Redirect
    */
    public function activate($slug)
    {
        $theme = new Theme;

        $theme->slug = $slug;
        $theme->name = str_replace('-', ' ', ucfirst($slug));
        $theme->user_id = Auth::user()->id;
        $theme->status = 0;
        $theme->custom = 0;

        $theme->save();

        return redirect()->route('admin.themes.index')->with('success', 'Theme has been activated.');
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
        $model = new Theme;

        $response = $model->simpleSave($request->all());

        return response()->json($response);
    }

    /**
    * Build
    *
    * @param Request $request
    * @param string $step
    *
    * @return View|string
    */
    public function build(Request $request, $step = null)
    {
        if ($request->getMethod() == 'POST') {
            switch($request->get('step')) {
                case 'customize': case 'snippets':
                        $request->session()->put('theme_builder', json_encode($request->all()));

                        return response()->json([
                            'status' => true
                        ]);
                    break;

                case 'completed':

                break;
            }
        } elseif($request->get('retrieve')) {
            return response()->json([
                'status' => true,
                'data' => json_decode($request->session()->get('theme_builder'))
            ]);
        }

        return view('themes::Admin/Themes/build', [ 'ignore_vuejs' => true ]);
    }
}
