<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EqualMonthlyPayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equal_monthly_pay', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creditid')->unsigned()->nullable();
            $table->double('monthly_pay');
            // Constraints declaration
            $table->foreign('creditid')->references('id')->on('credits_approved')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equal_monthly_pay');
    }
}
