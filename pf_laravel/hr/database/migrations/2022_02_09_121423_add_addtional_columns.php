<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddtionalColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workers', function(Blueprint $table) {
            $table->integer('specialization_id')->nullable();
            $table->integer('level_id')->nullable();
            $table->string('languages')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workers', function(Blueprint $table) {
            $table->dropColumn('specialization_id');
            $table->dropColumn('level_id');
            $table->dropColumn('languages');
        });
    }
}
