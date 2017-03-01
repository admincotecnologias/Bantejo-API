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
            $table->double('capital_balance',15,15); //Saldo Capital
            $table->double('interest_balance',15,15);// Saldo interes
            $table->double('iva_balance',15,15); // Saldo IVA
            $table->double('interest_arrear_balance',15,15); // Saldo Interes moratorio
            $table->double('interest_arrear_iva_balance',15,15); // Saldo iva moratorio
            $table->double('capital',15,15)->default(0); // Capital Abonado Total
            $table->double('interest',15,15)->default(0); // Interes generado del periodo
            $table->double('interest_arrear',15,15)->default(0); // Interes moratorio generado del periodo
            $table->double('iva',15,15)->default(0); // IVA generado del periodo
            $table->double('iva_arrear',15,15)->default(0); // IVA moratorio del periodo
            $table->double('pay',15,15)->default(0); // Pago total del periodo
            $table->double('pay_capital',15,15)->default(0);//pago al capital
            $table->double('pay_interest',15,15)->default(0);//pago al interes
            $table->double('pay_iva',15,15)->default(0); // pago al iva
            $table->double('pay_interest_arrear',15,15)->default(0); // pago al interes moratorio
            $table->double('pay_iva_arrear',15,15)->default(0); // pago al iva moratorio
            $table->float('type_currency',4,2)->default(1); // tipo de cambio si existe
            $table->string('currency')->default('MXN'); // tipo de cambio si existe
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
