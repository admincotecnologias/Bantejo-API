<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Files_Stock extends Model {

    protected $table = 'files_stock';
    use SoftDeletes;

	protected $fillable = [
	    'idstock','name','path','extension','mime','type',
    ];

	protected $dates = ['deleted_at',];



	// Relationships

}
