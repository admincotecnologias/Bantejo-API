<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditaidsTable extends Migration
{
    
    public function up()
    {
        Schema::create('creditaids', function(Blueprint $table) {
            $table->increments('id');
            // Schema declaration
            $table->integer('idapplication')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->string('lastname')->nullable();
            $table->string('rfc')->nullable();
            $table->string('curp')->nullable();
            $table->date('birthday')->nullable();
            $table->string('country')->nullable();
            $table->string('nacionality')->nullable();
            $table->string('email')->nullable();
            $table->string('fiel')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('maritalstatus')->nullable();
            $table->string('regimen')->nullable();
            $table->string('relationship')->nullable();
            $table->string('companyjob')->nullable();
            $table->string('phonejob')->nullable();
            $table->string('occupation')->nullable();
            $table->date('oldwork')->nullable();
            $table->string('typeguarantee');
            $table->integer('idguarantee')->unsigned()->nullable();
            // Constraints declaration
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('idapplication')->references('id')->on('applications')->onDelete('set null');
            $table->foreign('idguarantee')->references('id')->on('clients')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::drop('creditaids');
    }
}
