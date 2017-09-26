<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at',];

	protected $fillable = [];

	public static $rules = [
		// Validation rules
	];

	// Relationships

}
