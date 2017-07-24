<?php

namespace App\Modules\Users\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

use Validator;

use App\Modules\Users\Models\User;
use App\Modules\Users\Models\Role;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $items = User::orderBy('created_at', 'DESC');

        if ($request->get('status') != '') {
            $items->where('status', '=', $request->get('status'));
        }

        $items = $items->paginate(15);

        $roles = Role::all();

        return view('users::Admin/Users/index', [
            'items' => $items,
            'roles' => $roles
        ]);
    }

    public function add(Request $request)
    {
        $model = new User();

        if ($request->getMethod() == 'POST') {
            $this->validate($request, [
                'username' => 'required|unique:users|max:255',
                'email' => 'required|unique:users|max:255',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
                'first_name' => 'required',
                'last_name' => 'required'
            ]);

            $data = $request->all();

            $model->add($data);

            return redirect()->route('admin.users.index')->with('success', 'User has been saved');
        }

        return view('users::Admin/Users/add', [ 'model' => $model, 'roles' => $model->getRolesList() ]);
    }

    public function edit(Request $request, $id)
    {
        $model = User::find($id);

        if ($request->getMethod() == 'POST') {
            $validator = Validator::make($request->all(), [
                'username' => [
                      'required',
                      Rule::unique('users')->ignore($model->id)
                ],
                'email' => [
                      'required',
                      Rule::unique('users')->ignore($model->id)
                ],
                'password' => 'confirmed',
                'first_name' => 'required',
                'last_name' => 'required'
            ]);

            if (!$validator->fails()) {
                $data = $request->all();

                $model->edit($data);
            }

            return redirect()->route('admin.users.index')->with('success', 'User has been saved');
        }

        return view('users::Admin/Users/edit', [ 'model' => $model, 'roles' => $model->getRolesList() ]);
    }

    public function delete($id)
    {
        $model = User::find($id)->delete();

        return redirect()->route('admin.users.index')->with('success', 'User has been saved');
    }

    public function simpleSave(Request $request)
    {
        $model = new User;

        $response = $model->simpleSave($request->all());

        return response()->json($response);
    }

    public function loginAs(Request $request, $id)
    {
        $user = User::find($id);

        $request->session()->put('user', $user);

        return redirect()->route($user->getRedirectTo());
    }
}
