<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->text('first_link')->change();
            $table->text('first_title')->change();
            $table->text('first_description')->change();
            $table->text('first_content')->change();
            $table->text('rewriter_title')->nullable()->change();
            $table->text('rewriter_description')->nullable()->change();
            $table->text('rewriter_content')->nullable()->change();
            $table->text('last_link')->nullable()->change();
            $table->text('last_title')->nullable()->change();
            $table->text('last_description')->nullable()->change();
            $table->text('last_content')->nullable()->change();
            $table->text('first_category')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
