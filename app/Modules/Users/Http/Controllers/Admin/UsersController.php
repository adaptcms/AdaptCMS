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
        $query = User::orderBy('created_at', 'DESC');

		// status filter
        if ($request->get('status') != '') {
            $query->where('status', '=', $request->get('status'));
        }
        
        // role filter
        if ($role_id = $request->get('role_id')) {
        	$user_ids = User::hasRoleUserIds($role_id);
        
        	if (!empty($user_ids)) {
        		$query->whereIn('id', $user_ids);
        	} else {
        		// no user ID's for this role, return empty
        		$query->where('id', '=', '-1');
        	}
        }

        $items = $query->paginate(15);

        $user = new User;

        return view('users::Admin/Users/index', [
            'items' => $items,
            'roles' => $user->getRolesList()
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
                'last_name' => 'required',
                'roles' => 'required'
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
