<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailingResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailing_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mailing_id')->constrained();
            $table->foreignId('client_id')->constrained();
            $table->tinyInteger('success')->default(1);
            $table->text('error')->nullable();
            $table->timestamp('clicked_at')->nullable();
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
        Schema::dropIfExists('mailing_results');
    }
}
