<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailingButtons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailing_buttons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mailing_id')->constrained();
            $table->string('title');
            $table->string('action');
            $table->text('json')->nullable();
            $table->integer('sort')->default(100) ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mailing_buttons');
    }
}
