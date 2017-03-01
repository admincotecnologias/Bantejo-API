<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Files extends Model {
	use SoftDeletes;

	protected $fillable = ['idapplication','name','path','extension','mime',];

	protected $dates = ['deleted_at',];

	// Relationships

}
