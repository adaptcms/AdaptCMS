<?php

namespace App\Modules\Core\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Core\Models\Setting;
use App\Modules\Core\Models\SettingsCategory;

use Illuminate\Http\Request;

class SettingsController extends Controller
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
        $model = new Setting;

        $items = $model->getKeyedByCategory();

        return view('core::Admin/Settings/index', compact('items'));
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
        $model = new Setting;

        if ($request->getMethod() == 'POST') {
            $this->validate($request, [
                'key' => 'required|unique:settings|max:255',
                'value' => 'required'
            ]);

            $model->add($request->all());

            return redirect()->route('admin.settings.index')->with('success', 'The setting has been saved');
        }

        $categories = SettingsCategory::pluck('name', 'id');

        return view('core::Admin/Settings/add', compact('model', 'categories'));
    }

    /**
    * Add Category
    *
    * @param Request $request
    *
    * @return mixed
    */
    public function addCategory(Request $request)
    {
        $model = new SettingsCategory;

        if ($request->getMethod() == 'POST') {
            $this->validate($request, [
                'name' => 'required|unique:settings_categories|max:255'
            ]);

            $model->add($request->all());

            return redirect()->route('admin.settings.index')->with('success', 'The category has been saved');
        }

        return view('core::Admin/Settings/add_category', compact('model'));
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
        $model = new Setting;

        $response = $model->simpleSave($request->all());

        return response()->json($response);
    }
}
