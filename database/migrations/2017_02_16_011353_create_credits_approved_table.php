<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditsApprovedTable extends Migration
{
    
    public function up()
    {
        Schema::create('credits_approved', function(Blueprint $table) {
            $table->increments('id');
            // Schema declaration
            // Schema declaration
            $table->integer('type')->unsigned()->nullable(); //Tipo segun los definidos en la tabla de tipos
            $table->double('amount',15,15); //monto inicial del credito
            $table->date('start_date');  // Fecha de inicio del Credito
            $table->integer('term'); // No. de meses del credito
            $table->double('interest',15,15); // % de interes anual convertido a decimal
            $table->double('iva')->default(16/100); // iva actual
            $table->double('interest_arrear',15,15)->default(0); // Intereses moratorio
            $table->integer('grace_days')->default(31); // dias de periodo de gracia
            $table->string('currency')->default('MXN'); // tipo de cambio MXN/USD
            // Constraints declaration
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('type')->references('id')->on('credits_Available')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::drop('credits_approved');
    }
}
