<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('show');
            $table->boolean('insert');
            $table->boolean('edit');
            $table->boolean('delete');
            $table->boolean('report');
            $table->integer('iduser')->unsigned()->nullable();
            $table->integer('idpage')->unsigned()->nullable();
            $table->timestamps(); 
            $table->softDeletes();
            

            $table->foreign('iduser')->references('id')->on('users')->onDelete('set null');  

            $table->foreign('idpage')->references('id')->on('pages')->onDelete('set null');        
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
        Schema::dropIfExists('permissions');
    }
}
