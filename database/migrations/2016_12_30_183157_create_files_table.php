<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    
    public function up()
    {
        Schema::create('files', function(Blueprint $table) {
            $table->increments('id');
            // Schema declaration
            $table->integer('idapplication')->unsigned()->nullable();
            $table->string('name');
            $table->string('path');
            $table->string('extension');
            $table->string('mime');
            // Constraints declaration
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('idapplication')->references('id')->on('applications')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::drop('files');
    }
}
