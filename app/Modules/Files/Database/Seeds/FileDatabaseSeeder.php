<?php

namespace App\Modules\Files\Database\Seeds;

use Illuminate\Database\Seeder;

use App\Modules\Files\Models\Album;

class FileDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create default album
        $album = new Album();

        $album->add([
            'name' => 'Pictures',
            'user_id' => 1
        ]);
    }
}
