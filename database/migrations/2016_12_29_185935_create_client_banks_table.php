<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientBanksTable extends Migration
{
    
    public function up()
    {
        Schema::create('client_banks', function(Blueprint $table) {
            $table->increments('id');
            // Schema declaration
            $table->string('accounttype');
            $table->string('accountnumber');
            $table->string('clabe');
            $table->integer('idclient')->unsigned()->nullable();
            $table->integer('idbank')->unsigned()->nullable();
            // Constraints declaration
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('idclient')->references('id')->on('clients')->onDelete('set null');
            $table->foreign('idbank')->references('id')->on('banks')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::drop('client_banks');
    }
}
