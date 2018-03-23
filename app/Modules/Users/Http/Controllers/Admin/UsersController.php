<?php

namespace App\Modules\Users\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;
use App\Modules\Users\Models\User;
use App\Modules\Users\Models\Role;

use Validator;

class UsersController extends Controller
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
        $items = User::filter($request->all())->paginateFilter(15);
        
        $roles = (new User)->getRolesList();

        return view('users::Admin/Users/index', compact('items', 'roles'));
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
        $model = new User();

        if ($request->getMethod() == 'POST') {
            $this->validate($request, [
                'username' => 'required|unique:users|max:255',
                'email' => 'required|unique:users|max:255',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'roles' => 'required'
            ]);

            $data = $request->all();

            $model->add($data);

            return redirect()->route('admin.users.index')->with('success', 'User has been saved');
        }

        $roles = $model->getRolesList();
        $timezones = $model->getTimeZones();

        return view('users::Admin/Users/add', compact('model', 'roles', 'timezones'));
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
        $model = User::find($id);

        if (empty($model)) {
            abort(404);
        }

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
                'last_name' => 'required',
                'roles' => 'required'
            ]);

            if ($validator->fails()) {
                $request->session()->flash('error', 'Form errors found. Please try again.');
            } else {
                $data = $request->all();
                
                $model->edit($data);
                
                return redirect()->route('admin.users.index')->with('success', 'User has been saved');
            }
        }

        $roles = $model->getRolesList();
        $timezones = $model->getTimeZones();

        return view('users::Admin/Users/edit', compact('model', 'roles', 'timezones'));
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
        $model = User::find($id);

        if (empty($model)) {
            abort(404);
        }

        $model->delete();

        return redirect()->route('admin.users.index')->with('success', 'User has been saved');
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
        $model = new User;

        $response = $model->simpleSave($request->all());

        return response()->json($response);
    }

    /**
    * Login As
    *
    * @param Request $request
    * @param integer $id
    *
    * @return Redirect
    */
    public function loginAs(Request $request, $id)
    {
        $user = User::find($id);

        if (empty($user)) {
            abort(404);
        }

        $request->session()->put('user', $user);

        return redirect()->route($user->getRedirectTo());
    }
}
