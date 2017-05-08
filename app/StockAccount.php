<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockAccount extends Model {
    protected $table = 'StockAccount';
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
            'accounttype'=>'max:255|require',
            'accountnumber'=>'integer|require',
            'clabe'=>'integer|require',
            'idstock'=>'integer|require',
            'idbank'=>'integer|require',
        ],
        'update'=>[
            'accounttype'=>'max:255',
            'accountnumber'=>'integer',
            'clabe'=>'integer',
            'idstock'=>'integer',
            'idbank'=>'integer',
        ]
	];

	// Relationships

}
