<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLanguages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('title');
            $table->boolean('is_default')->nullable()->default(false);
            $table->timestamps();
        });

        DB::table('languages')->insert([
            [
                'code' => 'en',
                'title' => 'English',
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ru',
                'title' => 'Русский',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::dropIfExists('languages');
    }
}
