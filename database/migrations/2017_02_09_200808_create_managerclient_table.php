<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagerclientTable extends Migration
{
    
    public function up()
    {
        Schema::create('managerclient', function(Blueprint $table) {
             $table->increments('id');
            // Schema declaration
            // Schema declaration
            $table->integer('idclient')->unsigned()->nullable();
            $table->string('name');
            $table->string('lastname');
            $table->string('rfc')->nullable();
            $table->string('phone')->nullable();
            // Constraints declaration
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('idclient')->references('id')->on('clients')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::drop('managerclient');
    }
}
