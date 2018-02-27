<?php

namespace App\Modules\Posts\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use CustomFieldType;

class FieldTypesController
{
    /**
    * Index
    *
    * @return View
    */
    public function index()
    {
        $items = CustomFieldType::all();

        return view('posts::Admin/FieldTypes/index', compact('items'));
    }

    /**
    * Install
    *
    * @param string $slug
    *
    * @return Redirect
    */
    public function install($slug)
    {
        CustomFieldType::enable($slug);

        return redirect()->route('admin.field_types.index')->with('status', 'Field Type has been enabled.');
    }

    /**
    * Uninstall
    *
    * @param string $slug
    *
    * @return Redirect
    */
    public function uninstall($slug)
    {
        CustomFieldType::disable($slug);

        return redirect()->route('admin.field_types.index')->with('status', 'Field Type has been disabled.');
    }
}
