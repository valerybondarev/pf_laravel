<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommentField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('workers', 'comment') && !Schema::hasColumn('workers', 'comment_en')) {
            Schema::table('workers', function (Blueprint $table) {
                $table->text('comment')->nullable();
                $table->text('comment_en')->nullable();
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
        Schema::table('workers', function (Blueprint $table) {
            $table->dropColumn(['comment', 'comment_en']);
        });
    }
}
