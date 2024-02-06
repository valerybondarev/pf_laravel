<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('status');
            $table->integer('sort')->default(100);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('category_key_words', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained();
            $table->string('title');
        });
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('command')->nullable();
            $table->bigInteger('telegram_id');
            $table->string('telegram_status')->default('member');
            $table->string('username')->nullable();

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('year_in_tk')->nullable();

            $table->string('role')->default('user');

            $table->string('status')->default('active');
            $table->timestamp('subscribe_to')->nullable();
            $table->tinyInteger('demo_used')->default(0);
            $table->timestamp('registered_at')->nullable();
            $table->integer('points')->nullable();

            $table->integer('mailing_news')->default(1);

            $table->tinyInteger('start')->default(0);
            $table->timestamps();
        });
        Schema::create('client_category', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained();
            $table->foreignId('client_id')->constrained();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_category');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('category_key_words');
        Schema::dropIfExists('categories');
    }
}
