<?php
	
namespace App\Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Modules\Core\Models\Setting;
use App\Modules\Core\Models\SettingsCategory;

class SettingsAdminController extends Controller
{
	public function index(Request $request)
	{
        $model = new Setting;

        $items = $model->getKeyedByCategory();

        return view('core::SettingsAdmin/index', [ 'items' => $items ]);
	}

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

        return view('core::SettingsAdmin/add', compact('model', 'categories'));
    }

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

        return view('core::SettingsAdmin/add_category', compact('model'));
    }

    public function simpleSave(Request $request)
    {
        $model = new Setting;

        $response = $model->simpleSave($request->all());

        return response()->json($response);
    }
}