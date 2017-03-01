<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientShareholder extends Model {

	protected $table = 'client_shareholder';
	use SoftDeletes;

	protected $fillable = ['name','rfc','participation','occupation','oldwork','idprovider',];

	protected $dates = ['deleted_at',];

	// Relationships

}
