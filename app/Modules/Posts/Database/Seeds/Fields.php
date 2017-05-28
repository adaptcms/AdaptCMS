<?php

namespace App\Modules\Posts\Database\Seeds;

use Illuminate\Database\Seeder;

class Fields extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fields')->insert([
            'name' => 'Post Body',
            'slug' => 'post-body',
            'user_id' => 1,
            'category_id' => 1,
            'ord' => 0,
            'field_type' => 'textarea'
        ]);
    }
}
