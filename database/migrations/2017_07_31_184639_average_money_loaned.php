<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AverageMoneyLoaned extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('average_money_loaned')) {
            Schema::create('average_money_loaned', function(Blueprint $table) {
                $table->increments('id');
                // Schema declaration
                $table->integer('idsample');
                $table->integer('idclient')->nullable()->unsigned();
                $table->integer('money_loaned');

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
        Schema::drop('average_money_loaned');
    }
}
