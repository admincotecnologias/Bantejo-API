<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class controlcredit extends Model {

	protected $fillable = ['iva_arrear','pay','pay_capital','pay_interest','pay_iva','pay_interest_arrear','pay_iva_arrear','type_currency','currency',];

	protected $dates = ['deleted_at'];

	public static $rules = [
		// Validation rules
	];

	// Relationships

}
