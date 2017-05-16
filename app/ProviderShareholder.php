<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProviderShareholder extends Model {
	protected $table = 'stockholder_shareholder';
	use SoftDeletes;

	protected $fillable = [
	    'name',
        'lastname',
        'rfc',
        'email',
        'address',
        'colony',
        'postalcode',
        'city',
        'state',
        'phone',
        'idstockholder'
    ];

	protected $dates = ['deleted_at',];

    public static $rules = [
        // Validation rules
        'create'=>[
            'name'=>'required|max:255',
            'lastname'=>'required|max:255',
            'rfc'=>'required|max:255',
            'email'=>'required|max:255',
            'address'=>'required|max:255',
            'colony'=>'required|max:255',
            'postalcode'=>'required|max:255',
            'city'=>'required|max:255',
            'state'=>'required|max:255',
            'phone'=>'required|max:255',
            'idstockholder'=>'required|integer'
        ],
        'update'=>[
            'name'=>'max:255',
            'lastname'=>'max:255',
            'rfc'=>'max:255',
            'email'=>'max:255',
            'address'=>'max:255',
            'colony'=>'max:255',
            'postalcode'=>'max:255',
            'city'=>'max:255',
            'state'=>'max:255',
            'phone'=>'max:255',
            'idstockholder'=>'integer'
        ]
    ];

	// Relationships

}
