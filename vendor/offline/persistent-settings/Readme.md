# Persistent Settings Manager for Laravel 5
[![Build Status](https://travis-ci.org/OFFLINE-GmbH/persistent-settings.svg)](https://travis-ci.org/OFFLINE-GmbH/persistent-settings)

This package makes it easy to store persistent key/value settings in your Laravel 5 application. All settings are 
stored in your database and cached in a json file to minimize database queries.

## Install it
To install this package include it in your `composer.json` and run `composer update`:

    "require": {
       "offline/persistent-settings": "~1.0.0"
    }
     
Add the Service Provider to the `provider` array in your `config/app.php`

    'Offline\Settings\SettingsServiceProvider'
    
Add an alias for the facade to your `config/app.php`

    'Settings'  => 'Offline\Settings\Facades\Settings',

Publish the config and migration files:

    $ php artisan vendor:publish --provider="Offline\Settings\SettingsServiceProvider"
    
Change `config/settings.php` according to your needs. If you change `db_table`, don't forget to change the table's name
in the migration file as well.
    
Create the `settings` table. 

    $ php artisan migrate
    


## Use it

Set a value

    Settings::set('key', 'value');
    
Get a value

    $value = Settings::get('key');
    
Forget a value

    Settings::forget('key');

Forget all values

    Settings::flush();
    
