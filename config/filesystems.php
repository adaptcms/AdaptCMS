<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. A "local" driver, as well as a variety of cloud
    | based drivers are available for your choosing. Just store away!
    |
    | Supported: "local", "ftp", "s3", "rackspace"
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    */

    'disks' => [

        'base' => [
            'driver' => 'local',
            'root' => base_path()
        ],

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'framework-cache' => [
            'driver' => 'local',
            'root' => storage_path('framework/cache'),
        ],

        'framework-sessions' => [
            'driver' => 'local',
            'root' => storage_path('framework/sessions'),
        ],

        'framework-views' => [
            'driver' => 'local',
            'root' => storage_path('framework/views'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL')
        ],

        'app' => [
            'driver' => 'local',
            'root' => app_path(),
        ],

        // addons
        'themes' => [
            'driver' => 'local',
            'root' => public_path('themes')
        ],
        'plugins' => [
            'driver' => 'local',
            'root' => app_path('Modules')
        ],
        'field_types' => [
            'driver' => 'local',
            'root' => app_path('FieldTypes')
        ],

        // uploads
        'files' => [
            'driver' => 'local',
            'root' => public_path('uploads'),
            'path' => '/uploads'
        ],
        
        // adaptcms
        'cdn' => [
            'driver' => 's3',
        ]
    ]
];
