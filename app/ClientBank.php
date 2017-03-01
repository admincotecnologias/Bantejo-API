<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientBank extends Model {
	protected $table = 'client_banks';
	use SoftDeletes;

	protected $fillable = ['accounttype','accountnumber','accountage','idclient','idbank',];

	protected $dates = ['deleted_at',];

	

	// Relationships

}