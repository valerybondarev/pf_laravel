<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('disk');
            $table->string('name');
            $table->string('path');
            $table->string('extension');
            $table->integer('size');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('fileables', function (Blueprint $table) {
            $table->foreignUuid('file_id')->constrained('files')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('type')->nullable();
            $table->morphs('fileable');
        });

        Schema::create('images', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('disk');
            $table->string('name');
            $table->string('path');
            $table->string('extension');
            $table->integer('size');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('imageables', function (Blueprint $table) {
            $table->foreignUuid('image_id')->constrained('images')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('type')->nullable();
            $table->morphs('imageable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imageables');
        Schema::dropIfExists('images');

        Schema::dropIfExists('fileables');
        Schema::dropIfExists('files');
    }
}
