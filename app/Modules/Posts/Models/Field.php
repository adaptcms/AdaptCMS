<?php

namespace App\Modules\Posts\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

use App\Modules\Posts\Models\Category;

class Field extends Model implements Sortable
{
    use Searchable,
        Sluggable,
        SortableTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fields';

    protected $fillable = [
        'name',
        'caption',
        'field_type',
        'category_id'
    ];

    public $sortable = [
        'order_column_name' => 'ord',
        'sort_when_creating' => true,
    ];

    /**
    * Category
    *
    * @return Category
    */
    public function category()
    {
        return $this->belongsTo('App\Modules\Posts\Models\Category');
    }

    /**
    * Field Type
    *
    * @return FieldType
    */
    public function fieldType()
    {
        return $this->belongsTo('App\Modules\Posts\Models\FieldType');
    }

    /**
    * Get Categories
    *
    * @return array
    */
    public function getCategories()
    {
        return Category::pluck('name', 'id');
    }

    /**
    * Search Logic
    *
    * @param array $searchData
    *
    * @return array
    */
    public function searchLogic($searchData = [])
    {
        if (!empty($searchData['keyword'])) {
            $results = Field::search($searchData['keyword'])->get();
        } else {
            $results = [];
        }

        foreach ($results as $key => $row) {
            $results[$key]->url = route('admin.fields.edit', [ 'id' => $row->id ]);
        }

        return $results;
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

            switch ($data['type']) {
                case 'delete':
                    Field::whereIn('id', $data['ids'])->delete();
                break;
            }
        }

        return [
            'status' => true,
            'ids' => $data['ids']
        ];
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
        $this->caption = $postArray['caption'];
        $this->settings = json_encode($this->settings);
        $this->user_id = $postArray['user_id'];
        $this->field_type = $postArray['field_type'];
        $this->category_id = $postArray['category_id'];

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
        $this->caption = $postArray['caption'];
        $this->settings = json_encode($this->settings);
        $this->user_id = $postArray['user_id'];
        $this->field_type = $postArray['field_type'];
        $this->category_id = $postArray['category_id'];

        $this->save();

        return $this;
    }

    /**
    * Get Setting
    *
    * @param string $key
    *
    * @return mixed
    */
    public function getSetting($key)
    {
        $settings = json_decode($this->settings, true);

        return isset($settings[$key]) ? $settings[$key] : '';
    }

    /**
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
