<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

use App\Modules\Core\Models\SettingsCategory;

use Settings;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
        'category_id'
    ];
    
    public $timestamps = false;

    /**
    * Category
    *
    * @return SettingsCategory
    */
    public function category()
    {
        return $this->belongsTo('App\Modules\Core\Models\SettingsCategory', 'category_id');
    }

    /**
    * Get Keyed By Category
    *
    * @return array
    */
    public function getKeyedByCategory()
    {
        $categories = SettingsCategory::orderBy('name', 'ASC')->with('settings')->get();

        $settings = [];
        foreach($categories as $category) {
            $items = $category->settings;

            if (!empty($items)) {
                $settings[$category->name] = $items;
            }
        }

        return $settings;
    }

    /**
    * Add
    *
    * @param array $postArray
    *
    * @return Setting
    */
    public function add($postArray = [])
    {
        Settings::set($postArray['key'], $postArray['value']);

        $item = Setting::where('key', '=', $postArray['key'])->first();
        
        $item->category_id = $postArray['category_id'];

        $item->save();

        return $item;
    }

    /**
    * Edit
    *
    * @param array $postArray
    *
    * @return Setting
    */
    public function edit($postArray = [])
    {
        Settings::set($postArray['key'], $postArray['value']);

        $item = Setting::where('key', '=', $postArray['key'])->first();

        $item->category_id = $postArray['category_id'];

        $item->save();

        return $item;
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
            switch($data['type']) {
                case 'delete':
                    $data['ids'] = json_decode($data['ids'], true);

                    foreach($data['ids'] as $key) {
                        Settings::forget($key);
                    }
                break;

                case 'save':
                    foreach($data['data'] as $row) {
                        Settings::set($row['key'], $row['value']);
                    }
                break;
            }
        }

        return [
            'status' => true
        ];
    }
}