<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockAccount extends Model {
    protected $table = 'stockaccount';
    use SoftDeletes;

	protected $fillable = [
        'accounttype',
        'accountnumber',
        'clabe',
        'idstock',
        'idbank',
    ];

	protected $dates = ['deleted_at'];

	public static $rules = [
		// Validation rules
        'create'=>[
            'accounttype'=>'max:255|required',
            'accountnumber'=>'numeric|required',
            'clabe'=>'max:20|required',
            'idstock'=>'integer|required',
            'idbank'=>'integer|required',
        ],
        'update'=>[
            'accounttype'=>'max:255',
            'accountnumber'=>'numeric',
            'clabe'=>'max:20',
            'idstock'=>'integer',
            'idbank'=>'integer',
        ]
	];

	// Relationships

}
