<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldsTableFieldTypeChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fields', function (Blueprint $table) {
            if (Schema::hasColumn('fields', 'field_type')) {
                $table->dropColumn('field_type');
            }

            if (!Schema::hasColumn('fields', 'field_type_id')) {
                $table->integer('field_type_id')->nullable()->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fields', function (Blueprint $table) {
            if (!Schema::hasColumn('fields', 'field_type')) {
                $table->string('field_type');
            }

            if (Schema::hasColumn('fields', 'field_type_id')) {
                $table->dropColumn('field_type_id');
            }
        });
    }
}
