<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesStockTable extends Migration
{
    
    public function up()
    {
        Schema::create('files_stock', function(Blueprint $table) {
            $table->increments('id');
            // Schema declaration
            $table->integer('idstock')->unsigned()->nullable();
            $table->string('name');
            $table->string('path');
            $table->string('extension');
            $table->string('mime');
            $table->string('type');
            // Constraints declaration
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('idstock')->references('id')->on('stockholder')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::drop('files_stock');
    }
}
