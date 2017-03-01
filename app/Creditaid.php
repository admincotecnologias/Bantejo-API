<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Creditaid extends Model {

	protected $fillable = ['idapplication','name','lastname','rfc','curp','birthday','country','nacionality','email','fiel','address','phone','maritalstatus','regimen','relationship','companyjob','phonejob','occupation','oldwork',];

	protected $hidden = [
        'deleted_at', 
    ];
	protected $dates = ['deleted_at',];

}
