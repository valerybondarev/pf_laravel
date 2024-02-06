<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workers', function (Blueprint $table) {
            $table->integer('status_id');
            $table->integer('age')->nullable();
            $table->enum('sex', ['М', 'Ж'])->nullable();
            $table->date('birthday')->nullable();
            $table->integer('source_id')->nullable();
            $table->string('region')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('telegram')->nullable();
            $table->string('watsapp')->nullable();
            $table->string('vyber')->nullable();
            $table->string('skype')->nullable();
            $table->string('resume')->nullable();
            $table->text('experience')->nullable();
            $table->text('skills')->nullable();
            $table->text('education')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workers', function (Blueprint $table) {
            $table->dropColumn(['status_id', 'age', 'sex', 'birthday', 'source_id', 'region', 'phone', 'email', 'telegram', 'watsapp', 'vyber', 'skype', 'resume', 'experience', 'skills', 'education']);
        });
    }
}
