<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('experiences')) {
            Schema::create('experiences', function (Blueprint $table) {
                $table->id();
                $table->string('company')->nullable();
                $table->string('position')->nullable();
                $table->text('duties')->nullable();
                $table->string('company_en')->nullable();
                $table->string('position_en')->nullable();
                $table->text('duties_en')->nullable();
                $table->date('date_start')->nullable();
                $table->date('date_end')->nullable();
                $table->tinyInteger('current')->default(0);
                $table->integer('user_id')->index();
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
        Schema::dropIfExists('experiences');
    }
}
