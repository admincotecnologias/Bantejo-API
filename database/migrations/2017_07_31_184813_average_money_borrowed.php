<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AverageMoneyBorrowed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('average_money_borrowed')) {
            Schema::create('average_money_borrowed', function(Blueprint $table) {
                $table->increments('id');
                // Schema declaration
                $table->integer('idsample');
                $table->integer('idstockholder')->nullable()->unsigned();
                $table->integer('money_borrowed');
                $table->foreign('idstockholder')->references('id')->on('stockholder')->onDelete('set null');
                $table->timestamps();
                $table->softDeletes();
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('average_money_borrowed');
    }
}
