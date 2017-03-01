<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class creditavailable extends Model {
    use SoftDeletes;
    protected $table = 'credits_available';
	protected $fillable = ['name',];

	protected $dates = ['deleted_at',];

	public static $rules = [
		// Validation rules
	];

	// Relationships

}
