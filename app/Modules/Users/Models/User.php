<?php

namespace App\Modules\Users\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Scout\Searchable;

use App\Modules\Users\Models\Role;

use Auth;

class User extends Authenticatable
{
    use Notifiable;
    use Searchable;

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
		'role_id',
		'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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

    public function role()
    {
        return $this->belongsTo('App\Modules\Users\Models\Role');
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
	    return Role::pluck('name', 'id');
    }

    public function getRedirectTo()
    {
	    switch($this->role->slug) {
		    case 'admin':
		    	$route = 'admin.dashboard';
		    break;

		    case 'member':
		    	$route = 'home';
		    break;

        case 'editor':
          $route = 'admin.dashboard';
        break;
	    }

	    return $route;
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
	    $this->password = bcrypt($postArray['password']);
	    $this->email = $postArray['email'];
	    $this->status = 1;
	    $this->first_name = $postArray['first_name'];
	    $this->last_name = $postArray['last_name'];
	    $this->role_id = $postArray['role_id'];

      $this->save();

	    return $this;
    }

    public function edit($postArray)
    {
	    $this->username = $postArray['username'];
	    $this->email = $postArray['email'];
	    $this->first_name = $postArray['first_name'];
	    $this->last_name = $postArray['last_name'];
	    $this->role_id = $postArray['role_id'];

      $this->save();

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

    public function hasAccess($role = 'admin')
    {
        if (!Auth::check() || !$this->role || !$this->role->level) {
            return false;
        }

        $roleLevels = [
            'admin' => 3,
            'editor' => 2,
            'member' => 1
        ];

        $roleLevel = $this->role->level;

        return $roleLevel >= $roleLevels[$role];
    }
}
