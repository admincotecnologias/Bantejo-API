<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model {
	use SoftDeletes;

	protected $fillable = ['show','insert','edit','delete','report','iduser','idpage',];
	protected $hidden = [
        'deleted_at', 
    ];

	protected $dates = ['deleted_at',];

}
