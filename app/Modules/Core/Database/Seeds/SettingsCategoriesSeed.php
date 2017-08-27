<?php

namespace App\Modules\Core\Database\Seeds;

use Illuminate\Database\Seeder;

use App\Modules\Core\Models\SettingsCategory;

class SettingsCategoriesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$model = new SettingsCategory;
    	
    	$model->add([
    		'name' => 'Users'
    	]);
    	
    	$model = new SettingsCategory;
    	
    	$model->add([
    		'name' => 'General'
    	]);
    }
}
