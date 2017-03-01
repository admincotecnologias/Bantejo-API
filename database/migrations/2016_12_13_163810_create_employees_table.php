<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('lastname');
            $table->integer('iduser')->unsigned()->nullable();
            $table->integer('idoccupation')->unsigned()->nullable();
            $table->timestamps(); 
            $table->softDeletes();  

            $table->foreign('iduser')->references('id')->on('users')->onDelete('set null');  

            $table->foreign('idoccupation')->references('id')->on('occupations')->onDelete('set null');       
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
        Schema::dropIfExists('employees');
    }
}
