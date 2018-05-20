<?php

namespace App\Modules\Posts\Services;

use App\Modules\Posts\Models\FieldType;

use Cache;
use Storage;

class CustomFieldType
{
    public function sync()
    {
        // define cache value
        $directories = Storage::disk('field_types')->directories();
        $key = implode('|', $directories);

        if (Cache::get('field_types_sync') != $key) {
            $fieldTypes = collect([]);

            foreach ($directories as $directory) {
                $config = $directory . '/config.json';

                // check that config for field type exists
                if (Storage::disk('field_types')->exists($config)) {
                    $json = json_decode(Storage::disk('field_types')->get($config));

                    // ensure basic info exists
                    if (!empty($json->name) && !empty($json->version)) {
                        $json->slug = $directory;

                        $fieldTypes->push($json);
                    }
                }
            }

            // ensure there are valid field types that exist
            if ($fieldTypes->count()) {
                foreach ($fieldTypes as $fieldType) {
                    // update or create field type to sync data
                    FieldType::updateOrCreate(
                        [ 'name' => $fieldType->name ],
                        [
                            'name' => $fieldType->name,
                            'slug' => $fieldType->slug,
                            'version' => $fieldType->version,
                            'settings' => !empty($fieldType->settings) ? $fieldType->settings : []
                        ]
                    );
                }

                Cache::put('field_types_sync', $key);
            }
        }
    }

    /**
     * All
     * Returns all field types
     *
     * @return Collection
     */
    public function all()
    {
        return FieldType::all();
    }

    /**
     * Enable
     * Enables Field Type
     *
     * @param string $slug
     * 
     * @return void
     */
    public function enable($slug)
    {
        $slug = ucfirst(camel_case($slug));

        $fieldType = FieldType::whereSlug($slug)->first();

        if (!empty($fieldType)) {
            // set enabled value to databse
            $fieldType->enabled = true;

            $fieldType->save();

            $class = 'App\\FieldTypes\\' . $slug . '\\' . $slug . 'FieldType';

            // make sure class exists
            if (class_exists($class)) {
                $class = new $class;

                // fire off onEnable method if exists
                if (method_exists($class, 'onEnable')) {
                    $class->onEnable();
                }
            }
        }
    }

    /**
     * Disable
     * Disables Field Type
     *
     * @param string $slug
     * 
     * @return void
     */
    public function disable($slug)
    {
        $slug = ucfirst(camel_case($slug));

        $fieldType = FieldType::whereSlug($slug)->first();

        if (!empty($fieldType)) {
            // set enabled value to databse
            $fieldType->enabled = false;

            $fieldType->save();

            $class = 'App\\FieldTypes\\' . $slug . '\\' . $slug . 'FieldType';

            // make sure class exists
            if (class_exists($class)) {
                $class = new $class;

                // fire off onDisable method if exists
                if (method_exists($class, 'onDisable')) {
                    $class->onDisable();
                }
            }
        }
    }
}