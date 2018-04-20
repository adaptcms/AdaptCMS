<?php

namespace App\Modules\Users\Models;

use EloquentFilter\Filterable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Laravel\Scout\Searchable;
use RyanWeber\Mutators\Timezoned;
use Spatie\Permission\Traits\HasRoles;

use App\Modules\Users\Models\Role;

use Auth;
use Route;

class User extends Authenticatable
{
    use Notifiable,
        Searchable,
        HasRoles,
        HasApiTokens,
        Filterable,
        Timezoned;

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

    protected $casts = [
        'json' => 'array'
    ];

    protected $table = 'users';

    /**
    * Posts
    *
    * @return Collection
    */
    public function posts()
    {
        return $this->hasMany('App\Modules\Posts\Models\Post');
    }

    /**
    * Pages
    *
    * @return Collection
    */
    public function pages()
    {
        return $this->hasMany('App\Modules\Posts\Models\Page');
    }

    /**
    * Fields
    *
    * @return Collection
    */
    public function fields()
    {
        return $this->hasMany('App\Modules\Posts\Models\Field');
    }

    /**
    * Tags
    *
    * @return Collection
    */
    public function tags()
    {
        return $this->hasMany('App\Modules\Posts\Models\Tag');
    }

    /**
    * Themes
    *
    * @return Collection
    */
    public function themes()
    {
        return $this->hasMany('App\Modules\Themes\Models\Theme');
    }

    /**
    * Files
    *
    * @return Collection
    */
    public function files()
    {
        return $this->hasMany('App\Modules\Files\Models\File');
    }

    /**
    * Albums
    *
    * @return Collection
    */
    public function albums()
    {
        return $this->hasMany('App\Modules\Files\Models\Album');
    }

    /**
    * Set Password Attribute
    *
    * @param string $password
    *
    * @return void
    */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /**
    * To Searchable Array
    *
    * @return array
    */
    public function toSearchableArray()
    {
        return [
             'id' => $this->id,
             'username' => $this->username,
             'email' => $this->email,
             'name' => $this->getName()
        ];
    }

    /**
    * Update Last Logged In
    *
    * @return User
    */
    public function updateLastLoggedIn()
    {
        $this->last_login = new \DateTime();

        $this->save();

        return $this;
    }

    /**
    * Get Roles List
    *
    * @return array
    */
    public function getRolesList()
    {
        return Role::pluck('name', 'name');
    }

    /**
    * Get Roles List By Id
    *
    * @return array
    */
    public function getRolesListById()
    {
        return Role::pluck('name', 'id');
    }

    /**
    * Get Redirect To
    *
    * @return string
    */
    public function getRedirectTo()
    {
        $route_name = 'home';
        if ($this->roles->count()) {
            $role = $this->roles->sortByDesc(function($role, $key) {
                return $role->level;
            })->first();

            $route_name = $role->redirect_route_name;
        }

        return $route_name;
    }

    /**
    * Get Name
    *
    * @return string
    */
    public function getName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
    * Simple Save
    *
    * @param array $data
    *
    * @return array
    */
    public function simpleSave($data = [])
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

    /**
    * Search Logic
    *
    * @param array $searchData
    * @param bool $admin
    *
    * @return array
    */
    public function searchLogic($searchData = [], $admin = false)
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

    /**
    * Add
    *
    * @param array $postData
    *
    * @return User
    */
    public function add($postArray = [])
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

    /**
    * Edit
    *
    * @param array $postData
    *
    * @return User
    */
    public function edit($postArray = [])
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

    /**
    * Get Profile Image
    *
    * @param string $size
    *
    * @return string
    */
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

    /**
    * Is Allowed
    *
    * @param integer $access
    * @param null|string $route_name
    *
    * @return bool
    */
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

    public function modelFilter()
    {
        return $this->provideFilter(App\Modules\Users\ModelFilters\UserFilter::class);
    }

    public function getTimeZones()
    {
        return \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
    }
}
