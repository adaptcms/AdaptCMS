<?php

namespace App\Modules\Posts\ModelFilters;

use EloquentFilter\ModelFilter;

class PostFilter extends ModelFilter
{
    public function __construct($query, array $input = [], $relationsEnabled = true)
    {
        parent::__construct($query, $input, $relationsEnabled);

        $this->query->orderBy('name', 'ASC');
    }

    public function status($status = null)
    {
        return $this->where('status', '=', $status);
    }

    public function category($category_id)
    {
        return $this->where('category_id', '=', $category_id);
    }
}
