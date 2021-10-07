<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->text('first_link');
            $table->text('first_title');
            $table->text('first_description');
            $table->text('first_content');

            $table->text('rewriter_title')->nullable();
            $table->text('rewriter_description')->nullable();
            $table->text('rewriter_content')->nullable();
            
            $table->text('last_link')->nullable();
            $table->text('last_title')->nullable();
            $table->text('last_description')->nullable();
            $table->text('last_content')->nullable();

            $table->text('first_category')->nullable();
            $table->integer('status')->default(0); 
            $table->integer('website_id')->default(0); 
            $table->integer('bet_company_id')->default(1); 
            $table->integer('category_id')->default(1); 
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
        Schema::dropIfExists('contents');
    }
}
