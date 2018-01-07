<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Cviebrock\EloquentSluggable\Sluggable;

class SettingsCategory extends Model
{
    use Sluggable;

    protected $table = 'settings_categories';
    protected $fillable = [
        'name',
        'slug'
    ];

    /**
    * Settings
    *
    * @return Collection
    */
    public function settings()
    {
        return $this->hasMany('App\Modules\Core\Models\Setting', 'category_id');
    }

    /**
    * Add
    *
    * @param array $postArray
    *
    * @return SettingCategory
    */
    public function add($postArray)
    {
        $this->name = $postArray['name'];

        $this->save();

        return $this;
    }

    /**
    * Edit
    *
    * @param array $postArray
    *
    * @return SettingCategory
    */
    public function edit($postArray)
    {
        $this->name = $postArray['name'];

        $this->save();

        return $this;
    }
    
    /**
    * Sluggable
    * Return the sluggable configuration array for this model.
    *
    * @return array
    */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}