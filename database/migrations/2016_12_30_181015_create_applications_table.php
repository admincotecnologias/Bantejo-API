<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
    
    public function up()
    {
        Schema::create('applications', function(Blueprint $table) {
            $table->increments('id');
            // Schema declaration
            $table->integer('idclient')->unsigned()->nullable();
            $table->double('amountrequest', 12, 2);
            $table->date('applicationdate');
            $table->string('place');
            $table->integer('creditterm');
            $table->string('projectname');
            $table->string('status');
            // Constraints declaration
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('idclient')->references('id')->on('clients')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::drop('applications');
    }
}
