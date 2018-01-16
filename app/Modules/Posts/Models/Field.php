<?php

namespace App\Modules\Posts\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

use App\Modules\Posts\Models\Category;

class Field extends Model
{
    use Searchable,
        Sluggable;

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

    public $field_types = [
        'text' => 'Text',
        'textarea' => 'Text Area',
        'dropdown' => 'Dropdown Select',
        // 'multiple_dropdown' => 'Multi-Dropdown Select',
        // 'checkbox' => 'Check Box',
        'date' => 'Date Picker',
        // 'time' => 'Time Picker',
        // 'datetime' => 'Date & Time Picker',
        // 'file' => 'File Attachment',
        'image' => 'Image',
        // 'radio' => 'Radio',
        'email' => 'E-Mail Address',
        'wysiwyg' => 'WYSIWYG Editor',
        'code' => 'Code Editor'
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
        $this->ord = Field::count();

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
