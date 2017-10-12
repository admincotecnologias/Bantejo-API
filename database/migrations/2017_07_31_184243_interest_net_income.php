<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InterestNetIncome extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasTable('interest_net_income')) {
            Schema::create('interest_net_income', function(Blueprint $table) {
                $table->increments('id');
                // Schema declaration
                $table->integer('idsample');
                $table->integer('idclient')->unsigned()->nullable();
                $table->integer('interest_net_income');
                $table->foreign('idclient')->references('id')->on('clients')->onDelete('set null');
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
        Schema::drop('interest_net_income');
    }
}
