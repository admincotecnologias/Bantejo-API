<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model {
	use SoftDeletes;

	protected $fillable = ['name','lastname','type','businessname','employeenumber','rfc','fiel','email','businesscategory','constitutiondate','address','colony','postalcode','city','state','phone',];

	protected $dates = ['deleted_at','updated_at','created_at'];

	public static $rules =[
	    'create'=>[
            'businessname' => 'required|max:255|nullable',
            'name' => 'required|max:255|nullable',
            'lastname' => 'required|max:255|nullable',
            'type' => 'required|max:255',
            'employeenumber' => 'integer',
            'rfc' => 'required|max:255|unique:clients',
            'fiel' => 'max:255',
            'email' => 'required|max:255|email|unique:clients|unique:clients_user',
            'businesscategory' => 'required|max:255',
            'constitutiondate' => 'required|date',
            'address' => 'required|max:255',
            'colony' => 'required|max:255',
            'postalcode' => 'required|integer',
            'city' => 'required|max:255',
            'state' => 'required|max:255',
            'phone' => 'required|max:20',
        ],
        'update'=>[
            'businessname' => 'required|max:255|nullable',
            'name' => 'required|max:255|nullable',
            'lastname' => 'required|max:255|nullable',
            'type' => 'required|max:255',
            'employeenumber' => 'integer',
            'fiel' => 'max:255',
            'email' => 'max:255|email',
            'businesscategory' => 'max:255',
            'constitutiondate' => 'date',
            'address' => 'max:255',
            'colony' => 'max:255',
            'postalcode' => 'integer',
            'city' => 'max:255',
            'state' => 'max:255',
            'phone' => 'required|max:20',
        ]
    ];

	// Relationships

}
