<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manager extends Model {
	use SoftDeletes;

	protected $fillable = [];

	protected $dates = ['deleted_at',];

	

	// Relationships
	/**
	 * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'managerclient';
}
