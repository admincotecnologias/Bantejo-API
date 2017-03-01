<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stockholder extends Model {
	use SoftDeletes;
	
	protected $fillable = ['businessname','rfc','fiel','email','legalrepresentative','address','colony','postalcode','city','state','phone',];


	protected $dates = ['deleted_at'];

	

	// Relationships

}
