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
            $table->longText('first_link');
            $table->longText('first_title');
            $table->longText('first_description');
            $table->longText('first_content');

            $table->longText('rewriter_title')->nullable();
            $table->longText('rewriter_description')->nullable();
            $table->longText('rewriter_content')->nullable();
            
            $table->longText('last_link')->nullable();
            $table->longText('last_title')->nullable();
            $table->longText('last_description')->nullable();
            $table->longText('last_content')->nullable();

            $table->longText('first_category')->nullable();
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
