<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stockholder extends Model {
	use SoftDeletes;
	
	protected $fillable = [
        'businessname',
        'name',
        'lastname',
        'type', //Fisica o Moral
        //'rfc',
        'email',
        'legalrepresentative',
        'address',
        'colony',
        'postalcode',
        'city',
        'state',
        'phone',
        'nationality',
    ];


	protected $dates = ['deleted_at'];

    public static $rules = [
        // Validation rules
        'create'=>[
            'businessname'=>'max:255',
            'name'=>'max:255',
            'lastname'=>'max:255',
            'type'=>'require|max:255', //Fisica o Moral
            'rfc'=>'require|max:20',
            'email'=>'require|max:255',
            'legalrepresentative'=>'require|max:255',
            'address'=>'require|max:255',
            'colony'=>'require|max:255',
            'postalcode'=>'require|max:255',
            'city'=>'require|max:255',
            'state'=>'require|max:255',
            'phone'=>'require|max:255',
            'nationality'=>'require|max:255',
        ],
        'update'=>[
            'businessname'=>'max:255',
            'name'=>'max:255',
            'lastname'=>'max:255',
            'type'=>'max:255', //Fisica o Moral
            'rfc',
            'email'=>'max:255',
            'legalrepresentative'=>'max:255',
            'address'=>'max:255',
            'colony'=>'max:255',
            'postalcode'=>'max:255',
            'city'=>'max:255',
            'state'=>'max:255',
            'phone'=>'max:255',
            'nationality'=>'max:255',
        ]
    ];

	// Relationships

}
