<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditsAvailableTable extends Migration
{
    
    public function up()
    {
        Schema::create('credits_available', function(Blueprint $table) {
            $table->increments('id');
            // Schema declaration
            // Schema declaration
            $table->string('name');
            // Constraints declaration
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('credits_available');
    }
}
