<?php

namespace App\Modules\Posts\Database\Seeds;

use Illuminate\Database\Seeder;

class Pages extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages')->insert([
            'name' => 'Home',
            'slug' => 'home',
            'user_id' => 1,
            'status' => 1,
            'body' => '<p>Welcome Home!</p>',
            'ord' => 0
        ]);
    }
}
