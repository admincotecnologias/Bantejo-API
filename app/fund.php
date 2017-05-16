<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class fund extends Model {

    protected $table = 'fund';
    use SoftDeletes;

	protected $fillable = [
        'idstock',
        'amount',
        'start_date',
        'term',
        'interest',
        'iva',
        'interest_arrear',
        'grace_days',
        'currency',
        'todo',
        'status',
        'extends',
    ];

	protected $dates = ['deleted_at',];

	public static $rules = [
		// Validation rules
        'create'=>[
            'idstock'=>'required|integer',
            'amount'=>'required|numeric',
            'start_date'=>'required|date',
            'term'=>'required|integer',
            'interest'=>'required|numeric',
            'iva'=>'required|numeric',
            'interest_arrear'=>'required|numeric',
            'grace_days'=>'required|numeric',
            'currency'=>'required|max:5',
            'todo'=>'required|max:500',
            'status'=>'required|max:20',
            'extends'=>'required|integer|nullable',
        ]
	];

	// Relationships

}
