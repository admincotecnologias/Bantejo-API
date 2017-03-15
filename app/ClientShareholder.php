<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientShareholder extends Model {

	protected $table = 'client_shareholder';
	use SoftDeletes;

	protected $fillable = ['name','lastname','rfc','participation','rfc','oldwork','idclient',];

	protected $dates = ['deleted_at',];

	public static $rules = [
	    'create'=>[
            'name' => 'required|max:255',
            'lastname' => 'required|max:255',
            'rfc' => 'required|max:13',
            'participation' => 'required|integer',
            'oldwork' => 'required|date',
            'idclient' => 'required|integer',
        ],
        'update'=>[
            'name' => 'max:255',
            'lastname' => 'max:255',
            'rfc' => 'max:13',
            'participation' => 'integer',
            'oldwork' => 'date',
            'idclient' => 'integer',
        ]
    ];

	// Relationships

}
