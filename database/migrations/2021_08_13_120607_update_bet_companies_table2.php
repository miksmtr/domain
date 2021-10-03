<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBetCompaniesTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bet_companies', function (Blueprint $table) {
            $table->string('free_bonus')->default('0');
            $table->string('first_deposit')->default('0');
            $table->string('second_deposit')->default('0');
            $table->string('thirth_deposit')->default('0');
            $table->string('casino_bonus')->default('0');
            $table->string('link')->default('0');
            $table->integer('sort')->default('0');
        
            $table->integer('rating')->default('10');
            $table->integer('btc')->default('0');
            $table->integer('credit_card')->default('0');
            $table->integer('live_tv')->default('0');
            $table->integer('cash_out')->default('0');
            $table->integer('papara')->default('0');
            $table->integer('havale')->default('0');
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
