<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBanksTable extends Migration
{
    
    public function up()
    {
        Schema::create('banks', function(Blueprint $table) {
            $table->increments('id');
            // Schema declaration
            // Constraints declaration
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('banks');
    }
}
