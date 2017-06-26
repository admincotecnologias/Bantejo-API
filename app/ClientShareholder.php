<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientShareholder extends Model {

	protected $table = 'client_shareholder';
	use SoftDeletes;

	protected $fillable = ['name','lastname','rfc','participation','rfc','oldwork','idclient','type','businessname'];

	protected $dates = ['deleted_at',];

	public static $rules = [
	    'create'=>[
            'type'=>'required|max:255',
            'businessname'=>'required|max:255|nullable',
            'name' => 'required|max:255|nullable',
            'lastname' => 'required|max:255|nullable',
            'rfc' => 'required|max:13',
            'participation' => 'required|integer',
            'oldwork' => 'required|date',
            'idclient' => 'required|integer',
        ],
        'update'=>[
            'type'=>'max:255',
            'businessname' => 'max:255|nullable',
            'name' => 'max:255|nullable',
            'lastname' => 'max:255|nullable',
            'rfc' => 'max:13',
            'participation' => 'integer',
            'oldwork' => 'date',
            'idclient' => 'integer',
        ]
    ];

	// Relationships

}
