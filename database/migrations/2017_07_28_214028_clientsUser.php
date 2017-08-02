<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClientsUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientsuser', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('iduser')->unsigned()->nullable();
            // Schema declaration
            $table->string('email');
            $table->string('password');
            // Constraints declaration
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('iduser')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('clientsuser');
    }
}
