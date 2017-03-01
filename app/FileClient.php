<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileClient extends Model {

	protected $table = 'filesclient';
	use SoftDeletes;

	protected $fillable = ['idclient','name','path','extension','mime',];

	protected $dates = ['deleted_at',];

	// Relationships

}
