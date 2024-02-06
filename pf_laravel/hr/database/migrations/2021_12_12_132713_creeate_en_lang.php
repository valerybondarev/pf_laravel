<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreeateEnLang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('users', 'name_en')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('name_en')->nullable();
            });
        }
        if (!Schema::hasColumn('roles', 'name_en')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->string('name_en')->nullable();
            });
        }
        if (!Schema::hasColumn('skills', 'name_en')) {
            Schema::table('skills', function (Blueprint $table) {
                $table->string('name_en')->nullable();
            });
        }
        if (!Schema::hasColumn('sources', 'name_en')) {
            Schema::table('sources', function (Blueprint $table) {
                $table->string('name_en')->nullable();
            });
        }
        if (!Schema::hasColumn('statuses', 'name_en')) {
            Schema::table('statuses', function (Blueprint $table) {
                $table->string('name_en')->nullable();
            });
        }
        if (!Schema::hasColumn('workers', 'sex_en') && !Schema::hasColumn('workers', 'region_en') && !Schema::hasColumn('workers', 'education_en')) {
            Schema::table('workers', function (Blueprint $table) {
                $table->string('sex_en')->nullable();
                $table->string('region_en')->nullable();
                $table->string('education_en')->nullable();
                $table->text('experience_en')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['name_en']);
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn(['name_en']);
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn(['name_en']);
        });

        Schema::table('sources', function (Blueprint $table) {
            $table->dropColumn(['name_en']);
        });

        Schema::table('statuses', function (Blueprint $table) {
            $table->dropColumn(['name_en']);
        });

        Schema::table('workers', function (Blueprint $table) {
            $table->dropColumn(['sex_en', 'region_en', 'education_en', 'experience_en']);
        });
    }
}
