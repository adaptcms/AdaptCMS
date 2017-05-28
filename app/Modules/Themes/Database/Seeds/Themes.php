<?php

namespace App\Modules\Themes\Database\Seeds;

use Illuminate\Database\Seeder;

class Themes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('themes')->insert([
            'name' => 'Default',
            'slug' => 'default',
            'custom' => 0,
            'status' => 1,
            'user_id' => 1
        ]);
    }
}
