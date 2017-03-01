<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model {
	use SoftDeletes;

	protected $fillable = ['businessname','employeenumber','rfc','fiel','email','businesscategory','constitutiondate','address','colony','postalcode','city','state','phone',];

	protected $dates = ['deleted_at','updated_at','created_at'];

	

	// Relationships

}
