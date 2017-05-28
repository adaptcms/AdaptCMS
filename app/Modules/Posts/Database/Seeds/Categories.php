<?php

namespace App\Modules\Posts\Database\Seeds;

use Illuminate\Database\Seeder;

class Categories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'name' => 'Blog',
            'slug' => 'blog',
            'user_id' => 1,
            'ord' => 0
        ]);
    }
}
