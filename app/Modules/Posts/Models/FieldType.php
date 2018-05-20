<?php

namespace App\Modules\Posts\Models;

use Illuminate\Database\Eloquent\Model;

class FieldType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'version',
        'settings'
    ];

    protected $casts = [
        'settings' => 'array'
    ];

    public function fields()
    {
        return $this->hasMany('App\Modules\Posts\Models\Field');
    }

     /**
    * Add
    *
    * @param array $postArray
    *
    * @return Field
    */
    public function add($postArray = [])
    {
        $this->name = $postArray['name'];
        $this->slug = $postArray['slug'];
        $this->version = isset($postArray['version']) ? $postArray['version'] : null;
        $this->settings = !empty($postArray['settings']) ? $postArray['settings'] : [];

        $this->save();

        return $this;
    }

    /**
    * Edit
    *
    * @param array $postArray
    *
    * @return Field
    */
    public function edit($postArray = [])
    {
        $this->name = $postArray['name'];

        if (isset($postArray['slug'])) {
            $this->slug = $postArray['slug'];
        }

        if (isset($postArray['version'])) {
            $this->version = $postArray['version'];
        }

        if (isset($postArray['settings'])) {
            $this->settings = $postArray['settings'];
        }

        $this->save();

        return $this;
    }
}
