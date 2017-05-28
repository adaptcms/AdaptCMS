<?php

namespace App\Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**                                                                                                                                                                                                                                             
     * The table associated with the model.                                                                                                                                                                                                         
     *                                                                                                                                                                                                                                              
     * @var string                                                                                                                                                                                                                                  
     */
    protected $table = 'roles';
    
    public function users()
    {
        return $this->hasMany('App\Modules\Users\Models\User');
    }
}