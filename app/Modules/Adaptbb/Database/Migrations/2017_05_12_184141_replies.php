<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Replies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plugin_adaptbb_replies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('message');
            $table->tinyInteger('active')->default(1)->index();
            $table->tinyInteger('starred')->default(0)->index();
            $table->integer('star_ratings')->default(0)->index();
            $table->integer('forum_id')->index();
            $table->integer('topic_id')->index();
            $table->integer('user_id')->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('plugin_adaptbb_replies');
    }
}
