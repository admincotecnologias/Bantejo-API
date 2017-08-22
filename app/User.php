<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model {
	use SoftDeletes;

	protected $fillable = ['name', 'email', 'password','api_token','last_ip','last_connection',];

	protected $hidden = [
        'password', 
    ];

    protected $dates = ['deleted_at',];

	// Relationships

}
