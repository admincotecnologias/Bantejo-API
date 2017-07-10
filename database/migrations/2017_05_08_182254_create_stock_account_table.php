<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockAccountTable extends Migration
{
    
    public function up()
    {
        Schema::create('StockAccount', function(Blueprint $table) {
            $table->increments('id');
            // Schema declaration
            $table->string('accounttype');
            $table->string('accountnumber');
            $table->string('clabe');
            $table->integer('idstock')->unsigned()->nullable();
            $table->integer('idbank')->unsigned()->nullable();
            // Constraints declaration
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('idstock')->references('id')->on('stockholder')->onDelete('set null');
            $table->foreign('idbank')->references('id')->on('banks')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::drop('StockAccount');
    }
}
