<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CrateLanguageLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('language_levels')) {
            Schema::create('language_levels', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('name_en');
            });

            DB::statement("INSERT INTO language_levels (name, name_en) values ('Родной', 'Native')");
            DB::statement("INSERT INTO language_levels (name, name_en) values ('Продвинутый', '')");
        }

        Schema::table('workers', function(Blueprint $table) {
            $table->string('language_levels')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('language_levels');
        Schema::table('workers', function(Blueprint $table) {
            $table->dropColumn('language_levels');
        });
    }
}
