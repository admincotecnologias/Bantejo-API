<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model {
	use SoftDeletes;

	protected $fillable = ['amountrequest','amountsuccess','applicationdate','term','rate','arrears','periodicity','graceperiod','tax','interesttax','interest','status',];

	protected $dates = ['deleted_at',];

    public static $rules = [
        'create'=>[
            'amountrequest' => 'required|numeric',
            'applicationdate'=> 'required|date',
            'place'=> 'required|max:255',
            'creditterm'=> 'required|numeric',
            'projectname'=> 'required|max:255',
            'status'=> 'required|max:255',
            'idclient'=> 'required|integer',
        ],
        'update'=>[
            'amountrequest' => 'numeric',
            'applicationdate'=> 'date',
            'place'=> 'max:255',
            'creditterm'=> 'numeric',
            'projectname'=> 'max:255',
            'status'=> 'max:255',
            'idclient'=> 'integer',
        ]
    ];
}
