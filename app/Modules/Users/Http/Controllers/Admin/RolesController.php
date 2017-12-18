<?php

namespace App\Modules\Users\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

use Validator;

use App\Modules\Users\Models\User;
use App\Modules\Users\Models\Role;

class RolesController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::orderBy('created_at', 'DESC');

        $items = $query->paginate(15);

        return view('users::Admin/Roles/index', [
            'items' => $items
        ]);
    }

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

        return view('users::Admin/Roles/add', [ 'model' => $model, 'roles' => $model->getRolesList() ]);
    }

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

        return view('users::Admin/Roles/edit', [ 'model' => $model, 'roles' => $model->getRolesList() ]);
    }

    public function delete($id)
    {
        $model = Role::find($id)->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role has been deleted');
    }
}
