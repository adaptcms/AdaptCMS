<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Roles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->integer('level')->index();
            $table->tinyInteger('core_role')->default(0)->index();
            $table->string('redirect_route_name')->nullable();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	// vendor package will drop this table for us, should at least
    	if (!Schema::hasTable('roles')) {
    		Schema::drop('roles');
    	}
    }
}
