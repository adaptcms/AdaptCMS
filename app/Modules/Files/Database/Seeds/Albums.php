<?php

namespace App\Modules\Files\Database\Seeds;

use Illuminate\Database\Seeder;

class Albums extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('albums')->insert([
            'name' => 'Pictures',
            'slug' => 'pictures',
            'user_id' => 1
        ]);
    }
}
