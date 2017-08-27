<?php

namespace App\Modules\Users\Models;

use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Scout\Searchable;
use Laravel\Passport\HasApiTokens;

use App\Modules\Users\Models\Role;

use Auth;
use Route;
use DB;

class User extends Authenticatable
{
    use Notifiable,
        Searchable,
        HasRoles,
        HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
		'status',
        'settings'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $table = 'users';

    public function posts()
    {
	    return $this->hasMany('App\Modules\Posts\Models\Post');
    }

    public function pages()
    {
	    return $this->hasMany('App\Modules\Posts\Models\Page');
    }

    public function fields()
    {
	    return $this->hasMany('App\Modules\Posts\Models\Field');
    }

    public function tags()
    {
	    return $this->hasMany('App\Modules\Posts\Models\Tag');
    }

    public function themes()
    {
        return $this->hasMany('App\Modules\Themes\Models\Theme');
    }

    public function files()
    {
	    return $this->hasMany('App\Modules\Files\Models\File');
    }

    public function albums()
    {
	    return $this->hasMany('App\Modules\Files\Models\Album');
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function toSearchableArray()
    {
        return [
             'id' => $this->id,
             'username' => $this->username,
             'email' => $this->email,
             'name' => $this->getName()
        ];
    }

    public function updateLastLoggedIn()
    {
        $this->last_login = new \DateTime();
        $this->save();
    }

    public function getRolesList()
    {
	    return Role::pluck('name', 'name');
    }

    public function getRedirectTo()
    {
        $route_name = 'home';
        $roles = $this->roles;

        if ($this->roles->count()) {
            $role = $this->roles->sortByDesc(function($role, $key) {
                return $role->level;
            })->first();

            $route_name = $role->redirect_route_name;
        }

		return $route_name;
    }

    public function getName()
    {
	    return $this->first_name . ' ' . $this->last_name;
    }

    public function simpleSave($data)
    {
        if (!empty($data['many'])) {
            $data['ids'] = json_decode($data['ids'], true);

            switch($data['type']) {
            	case 'delete':
                	User::whereIn('id', $data['ids'])->delete();
                break;

				case 'toggle-statuses':
	                $active_items = User::whereIn('id', $data['ids'])->where('status', '=', 1)->get();
	                $pending_items = User::whereIn('id', $data['ids'])->where('status', '=', 0)->get();

	                foreach($active_items as $item) {
	                    $item->status = 0;

	                    $item->save();
	                }

	                foreach($pending_items as $item) {
	                    $item->status = 1;

	                    $item->save();
	                }

                break;
            }
        }

        return [
            'status' => true,
            'ids' => $data['ids']
        ];
    }

    public function searchLogic($searchData, $admin = false)
    {
        if (!empty($searchData['keyword'])) {
            $results = User::search($searchData['keyword'])->get();
        } else {
            $results = [];
        }

        foreach($results as $key => $row) {
            if ($admin) {
                $results[$key]->url = route('admin.users.edit', [ 'id' => $row->id ]);
            } else {
                $results[$key]->url = route('users.view', [ 'username' => $row->username ]);
            }
        }

        return $results;
    }

    public function add($postArray)
    {
	    $this->username = $postArray['username'];
	    $this->password = $postArray['password'];
	    $this->email = $postArray['email'];
	    $this->status = 1;
        $this->settings = json_encode( (!empty($postArray['settings']) ? $postArray['settings'] : []) );
	    $this->first_name = $postArray['first_name'];
	    $this->last_name = $postArray['last_name'];
	    
		// save the record
        $this->save();
        
        // sync roles after saving
        if (!empty($postArray['roles'])) {
        	$this->syncRoles($postArray['roles']);
        } else {
        	// assign member level role
        	$member_role = Role::byLevel(1);
        	
        	$this->syncRoles([ $member_role->name ]);
        }

	    return $this;
    }

    public function edit($postArray)
    {
	    $this->username = $postArray['username'];
	    $this->email = $postArray['email'];
	    $this->first_name = $postArray['first_name'];
	    $this->last_name = $postArray['last_name'];

        if (isset($postArray['status'])) {
            $this->status = $postArray['status'];
        }

        $this->settings = json_encode( (!empty($postArray['settings']) ? $postArray['settings'] : []) );
        
        // save the record
        $this->save();
        
        // sync roles after saving
        $this->syncRoles($postArray['roles']);

	    return $this;
    }

    public function getProfileImage($size = 'small')
    {
	    if (!empty($this->profile_image)) {
		    $image = $this->profile_image;
	    } else {
		    switch($size) {
			    case 'small':
			    	$image = 'http://placehold.it/50x50?text=No Image';
			    break;

			    case 'medium':
			    	$image = 'http://placehold.it/150x150?text=No Image';
			    break;

			    case 'large':
			    	$image = 'http://placehold.it/350x350?text=No Image';
			    break;
		    }
	    }

	    return $image;
    }

    public function isAllowed($access = 1, $route_name = null)
    {
        if (!Auth::check() || !$this->roles->count()) {
            return false;
        }

        if (empty($route_name)) {
            $route_name = Route::current()->getName();
        }

        $permission = $this->getAllPermissions()->first(function($val, $key) use ($route_name) {
            return $val->name = $route_name;
        });

        return !empty($permission) && $permission->access >= $access;
    }
    
    /**
    * Has Role User Ids
    *
    * Returns user ID's that have at least the
    * specified role id
    *
    * @return array
    */
    public static function hasRoleUserIds($role_id)
    {
    	$model_type = 'App\Modules\Users\Models\User';
    
    	$user_ids = DB::table('model_has_roles')
    		->where('role_id', '=', $role_id)
    		->where('model_type', '=', $model_type)
    		->pluck('model_id');
    	
    	return $user_ids->toArray();
    }
}
