<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Topics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plugin_adaptbb_topics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('message');
            $table->string('icon')->nullable();
            $table->string('topic_type')->index();
            $table->tinyInteger('active')->default(0)->index();
            $table->tinyInteger('locked')->default(0)->index();
            $table->tinyInteger('starred')->default(0)->index();
            $table->integer('forum_id')->index();
            $table->integer('user_id')->index();
            $table->integer('views')->default(0);
            $table->integer('replies_count')->default(0)->index();

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
        Schema::drop('plugin_adaptbb_topics');
    }
}
