<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProviderShareholderTable extends Migration
{
    
    public function up()
    {
        Schema::create('stockholder_shareholder', function(Blueprint $table) {
            $table->increments('id');
            // Schema declaration
            $table->string('name');
            $table->string('lastname');
            $table->string('rfc');
            $table->string('participation');
            $table->string('occupation');
            $table->date('oldwork');
            $table->integer('idstockholder')->unsigned()->nullable();
            // Constraints declaration
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('idstockholder')->references('id')->on('stockholder')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::drop('stockholder_shareholder');
    }
}
