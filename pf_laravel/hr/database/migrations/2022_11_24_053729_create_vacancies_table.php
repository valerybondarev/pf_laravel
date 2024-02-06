<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en');
            $table->integer('specialization_id')->nullable();
            $table->string('salary_from')->nullable();
            $table->string('salary_from_en')->nullable();
            $table->string('salary_to')->nullable();
            $table->string('salary_to_en')->nullable();
            $table->enum('taxes', ['до вычета налогов', 'на руки']);
            $table->string('experience')->nullable();
            $table->string('experience_en')->nullable();
            $table->text('requirements');
            $table->text('requirements_en');
            $table->text('skills')->nullable();
            $table->string('slug')->nullable();
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
        Schema::dropIfExists('vacancies');
    }
}
