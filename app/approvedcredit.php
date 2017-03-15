<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class approvedcredit extends Model {
    protected $table = 'credits_approved';
    use SoftDeletes;

	protected $fillable = ['application','type','amount','start_date','term','interest','iva','interest_arrear','grace_days','currency',];

	protected $dates = ['deleted_at',];

	public static $rules = [
        'create'=>[
            'application'=>'required|integer',
            'type'=>'required|integer',
            'amount'=>'required|numeric',
            'start_date'=>'required|date',
            'term'=>'required|integer',
            'interest'=>'required|numeric',
            'iva'=>'required|numeric',
            'interest_arrear'=>'required|numeric',
            'grace_days'=>'required|numeric',
            'currency'=>'required|max:5'
        ]
    ];
	// Relationships

}
