<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientShareholderTable extends Migration
{
    
    public function up()
    {
        Schema::create('client_shareholder', function(Blueprint $table) {
            $table->increments('id');
            // Schema declaration
            $table->string('name');
            $table->string('rfc');
            $table->string('participation');
            $table->string('lastname');
            $table->date('oldwork');
            $table->integer('idclient')->unsigned()->nullable();
            // Constraints declaration
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('idclient')->references('id')->on('clients')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::drop('client_shareholder');
    }
}
