<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalysisfilesTable extends Migration
{

    public function up()
    {
        if(!Schema::hasTable('analysisfiles')){
            Schema::create('analysisfiles', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('analysisid')->unsigned()->nullable();
                $table->integer('fileid')->unsigned()->nullable();
                // Schema declaration
                // Constraints declaration
                $table->foreign('analysisid')->references('id')->on('creditanalysis')->onDelete('set null');
                $table->foreign('fileid')->references('id')->on('files')->onDelete('set null');
                $table->timestamps();
                $table->softDeletes();
            });
        }

    }

    public function down()
    {
        Schema::drop('analysisfiles');
    }
}
