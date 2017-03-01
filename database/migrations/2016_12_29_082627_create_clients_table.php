<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    
    public function up()
    {
        Schema::create('clients', function(Blueprint $table) {
            $table->increments('id');
            // Schema declaration
            $table->string('businessname');
            $table->string('employeenumber')->nullable();
            $table->string('rfc');
            $table->string('fiel')->nullable();
            $table->string('email');
            $table->string('businesscategory');
            $table->date('constitutiondate');
            $table->string('address');
            $table->string('colony');
            $table->string('postalcode');
            $table->string('city');
            $table->string('state');
            $table->string('phone');
            // Constraints declaration
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('clients');
    }
}
