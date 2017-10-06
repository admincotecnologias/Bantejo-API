<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class managerclient extends Model {
	// Relationships
	/**
	 * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'managerclient';

	use SoftDeletes;

	protected $fillable = ['idclient','name','lastname','rfc','phone',];

	protected $dates = ['deleted_at',];


	// Relationships

}
