<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FileClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filesclient', function (Blueprint $table) {
            $table->increments('id');
            // Schema declaration
            $table->integer('idclient')->unsigned()->nullable();
            $table->string('name');
            $table->string('path');
            $table->string('extension');
            $table->string('mime');
            $table->string('type');
            // Constraints declaration
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('idclient')->references('id')->on('clients')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filesclient');
    }
}
