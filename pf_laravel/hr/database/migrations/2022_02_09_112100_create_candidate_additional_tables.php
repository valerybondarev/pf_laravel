<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCandidateAdditionalTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('specializations')) {
            Schema::create('specializations', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('name_en');
            });
        }

        if (!Schema::hasTable('levels')) {
            Schema::create('levels', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('name_en');
            });
        }

        if (!Schema::hasTable('languages')) {
            Schema::create('languages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('name_en');
            });
        }

        DB::statement("INSERT INTO specializations (name) values ('Full-Stack разработчик')");
        DB::statement("INSERT INTO levels (name) values ('Junior'), ('Middle'), ('Middle+'), ('Senior')");
        DB::statement("INSERT INTO languages (name) values ('Английский'), ('Немецкий'), ('Русский')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('specializations');
        Schema::dropIfExists('levels');
        Schema::dropIfExists('languages');
    }
}
