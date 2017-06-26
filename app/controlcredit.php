<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class controlcredit extends Model {

	protected $fillable = ['credit','period','capital_balance','interest_balance','iva_balance',
        'interest_arrear_balance','interest_arrear_iva_balance','capital','interest','interest_arrear','iva',
        'iva_arrear','pay','pay_capital','pay_interest','pay_iva','pay_interest_arrear','pay_iva_arrear','type_currency',
        'currency','typemove','idref'
    ];

	protected $dates = ['deleted_at'];

	public static $rules = [
		// Validation rules
        'create'=>[
            'credit'=>'required|integer',
            'period'=>'required|date',
            'capital_balance'=>'required|numeric',
            'interest_balance'=>'required|numeric',
            'iva_balance'=>'required|numeric',
            'interest_arrear_balance'=>'required|numeric',
            'interest_arrear_iva_balance'=>'required|numeric',
            'capital'=>'required|numeric',
            'interest'=>'required|numeric',
            'interest_arrear'=>'required|numeric',
            'iva'=>'required|numeric',
            'iva_arrear'=>'required|numeric',
            'pay'=>'required|numeric',
            'pay_capital'=>'required|numeric',
            'pay_interest'=>'required|numeric',
            'pay_iva'=>'required|numeric',
            'pay_interest_arrear'=>'required|numeric',
            'pay_iva_arrear'=>'required|numeric',
            'type_currency'=>'required|numeric',
            'currency'=>'required|string',
            'typemove'=>'string|nullable',
            'idref'=>'integer|nullable'
        ]
	];

	// Relationships

}
