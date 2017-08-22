<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NPLRatio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasTable('npl_ratio')) {
            Schema::create('npl_ratio', function(Blueprint $table) {
                $table->increments('id');
                // Schema declaration
                $table->integer('idsample');
                $table->integer('idclient')->nullable()->unsigned();
                $table->integer('active_money');
                $table->integer('grace_money');
                $table->integer('expired_money');
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
        Schema::drop('npl_ratio');
    }
}
