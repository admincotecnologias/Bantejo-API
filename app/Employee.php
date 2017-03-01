<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model {
	use SoftDeletes;

	protected $fillable = ['name','lastname','iduser','idoccupation',];

	protected $hidden = [
        'deleted_at', 
    ];
	protected $dates = ['deleted_at',];

}
