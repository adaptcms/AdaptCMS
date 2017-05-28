<?php

namespace App\Modules\Users\Database\Seeds;

use Illuminate\Database\Seeder;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'Member',
                'level' => 1
            ],
            [
                'name' => 'Editor',
                'level' => 2
            ],
            [
                'name' => 'Admin',
                'level' => 3
            ]
        ];

        foreach($roles as $role) {
            DB::table('roles')->insert([
                'name' => $role['name'],
                'slug' => str_slug($role['name'], '-'),
                'user_id' => 1,
                'level' => $role['level']
            ]);
        }
    }
}
