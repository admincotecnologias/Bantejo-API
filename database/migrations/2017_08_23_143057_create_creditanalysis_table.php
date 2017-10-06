<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditanalysisTable extends Migration
{

    public function up()
    {
        Schema::create('creditanalysis', function(Blueprint $table) {
            if(!Schema::hasTable('creditanalysis')) {
                $table->increments('id');
                $table->integer('applicationid')->unsigned()->nullable();
                $table->string('observation');
                $table->date('start_date');
                // Schema declaration
                // Constraints declaration
                $table->foreign('applicationid')->references('id')->on('applications')->onDelete('set null');
                $table->timestamps();
                $table->softDeletes();
            }
        });
    }

    public function down()
    {
        Schema::drop('creditanalysis');
    }
}
