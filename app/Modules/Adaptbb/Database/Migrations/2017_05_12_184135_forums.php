<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Forums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plugin_adaptbb_forums', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->integer('ord')->default(0)->index();
            $table->text('notice')->nullable();
            $table->tinyInteger('locked')->default(0)->index();
            $table->text('description')->nullable();
            $table->string('backgroundColor')->nullable();
            $table->string('icon')->nullable();
            $table->string('borderColor')->nullable();
            $table->string('borderWidth')->nullable();
            $table->integer('category_id')->index();
            $table->integer('views')->default(0)->index();
            $table->integer('topics_count')->default(0)->index();
            $table->integer('replies_count')->default(0)->index();

            $table->string('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();

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
        Schema::drop('plugin_adaptbb_forums');
    }

}
