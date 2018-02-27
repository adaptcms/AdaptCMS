<?php
    
namespace App\Modules\Posts\Facades;

use Illuminate\Support\Facades\Facade;

class CustomFieldType extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'customFieldType';
    }
}