<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Creditaid extends Model {

	protected $fillable = ['idapplication','name','lastname','rfc','curp','birthday','country','nacionality','email','fiel','address','phone','maritalstatus','regimen','relationship','companyjob','phonejob','occupation','oldwork',];

	protected $hidden = [
        'deleted_at', 
    ];
	protected $dates = ['deleted_at',];

	public static $rules=[
	    'moral'=>[
	        'create'=>[
                'idapplication' => 'required|integer',
                'typeguarantee'=> 'required|max:255',
                'idguarantee'=> 'required|integer',
            ]
        ],
        'fisica'=>[
            'create'=>[
                'idapplication' => 'required|integer',
                'name'=> 'required|max:255',
                'lastname'=> 'required|max:255',
                'rfc'=> 'required|max:13',
                'curp'=> 'required|max:255',
                'birthday'=> 'required|date',
                'country'=> 'required|max:255',
                'nacionality'=> 'required|max:255',
                'email'=> 'required|email|max:255',
                'fiel'=> 'nullable|max:255',
                'address'=> 'required|max:255',
                'phone'=> 'required|max:255',
                'maritalstatus'=> 'required|max:255',
                'regimen'=> 'nullable|max:255',
                'relationship'=> 'required|max:255',
                'companyjob'=> 'required|max:255',
                'phonejob'=> 'required|max:255',
                'occupation'=> 'required|max:255',
                'oldwork'=> 'required|date'
            ]
        ]
    ];

}
