<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class SettingsCategory extends Model
{
    protected $table = 'settings_categories';
    protected $fillable = [
        'name',
        'slug'
    ];

    public function settings()
    {
        return $this->hasMany('App\Modules\Core\Models\Setting', 'category_id');
    }

    public function add($postArray)
    {
        $this->name = $postArray['name'];
        $this->slug = str_slug($this->name, '-');

        $this->save();
    }

    public function edit($postArray)
    {
        $this->name = $postArray['name'];
        $this->slug = str_slug($this->name, '-');

        $this->save();
    }
    
}