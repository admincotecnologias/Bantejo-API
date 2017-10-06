<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockholderTable extends Migration
{
    
    public function up()
    {
        Schema::create('stockholder', function(Blueprint $table) {
            $table->increments('id');
            // Schema declaration
            $table->string('businessname')->nullable();
            $table->string('name')->nullable();
            $table->string('lastname')->nullable();
            $table->string('type');//Fisica o Moral
            $table->string('rfc');
            $table->string('email');
            $table->string('address');
            $table->string('colony');
            $table->string('postalcode');
            $table->string('city');
            $table->string('state');
            $table->string('phone');
            $table->string('nationality');
            // Constraints declaration
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('stockholder');
    }
}
