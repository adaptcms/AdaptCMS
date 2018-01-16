<?php

namespace App\Modules\Users\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;
use App\Modules\Users\Models\User;
use App\Modules\Users\Models\Role;

use Validator;

class RolesController extends Controller
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
        $query = Role::orderBy('created_at', 'DESC');

        $items = $query->paginate(15);

        return view('users::Admin/Roles/index', compact('items'));
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
        $model = new Role();

        if ($request->getMethod() == 'POST') {
            $this->validate($request, [
                'name' => 'required|unique:roles|max:255',
                'level' => 'required',
                'redirect_route_name' => 'required',
                'permissions' => 'required'
            ]);

            $data = $request->all();

            $model->add($data);

            return redirect()->route('admin.roles.index')->with('success', 'Role has been created');
        }

        $roles = $model->getRolesList();

        return view('users::Admin/Roles/add', compact('model', 'roles'));
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
        $model = Role::find($id);

        if ($request->getMethod() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => [
                      'required',
                      Rule::unique('roles')->ignore($model->id)
                ],
                'level' => 'required',
                'redirect_route_name' => 'required',
                'permissions' => 'required'
            ]);

            if ($validator->fails()) {
                $request->session()->flash('error', 'Form errors found. Please try again.');
            } else {
                $data = $request->all();
                
                $model->edit($data);
                
                return redirect()->route('admin.roles.index')->with('success', 'Role has been saved');
            }
        }

        $roles = $model->getRolesList();

        return view('users::Admin/Roles/edit', compact('model', 'roles'));
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
        $model = Role::find($id);

        if (empty($model)) {
            abort(404);
        }

        $model->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role has been deleted');
    }
}
