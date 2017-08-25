<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateControlcreditsTable extends Migration
{
    
    public function up()
    {
        Schema::create('controlcredits', function(Blueprint $table) {
            $table->increments('id');
            // Schema declaration
            $table->integer('credit')->unsigned()->nullable();//Credito padre
            $table->date('period'); //Fecha del periodo
            $table->double('capital_balance'); //Saldo Capital
            $table->double('interest_balance');// Saldo interes
            $table->double('iva_balance'); // Saldo IVA
            $table->double('interest_arrear_balance'); // Saldo Interes moratorio
            $table->double('interest_arrear_iva_balance'); // Saldo iva moratorio
            $table->double('capital')->default(0); // Capital Abonado Total
            $table->double('interest')->default(0); // Interes generado del periodo
            $table->double('interest_arrear')->default(0); // Interes moratorio generado del periodo
            $table->double('iva')->default(0); // IVA generado del periodo
            $table->double('iva_arrear')->default(0); // IVA moratorio del periodo
            $table->double('pay')->default(0); // Pago total del periodo
            $table->double('pay_capital')->default(0);//pago al capital
            $table->double('pay_interest')->default(0);//pago al interes
            $table->double('pay_iva')->default(0); // pago al iva
            $table->double('pay_interest_arrear')->default(0); // pago al interes moratorio
            $table->double('pay_iva_arrear')->default(0); // pago al iva moratorio
            $table->float('type_currency')->default(1); // tipo de cambio si existe
            $table->string('currency')->default('MXN'); // tipo de cambio si existe
            $table->string('typemove')->nullable()->default(null); // tipo de movimiento si existe
            $table->integer('idref')->nullable()->default(null); // id de archivo si existe
            // Constraints declaration
            $table->foreign('credit')->references('id')->on('credits_approved')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('controlcredits');
    }
}
