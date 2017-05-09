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
            'name'=>'require|max:255',
            'lastname'=>'require|max:255',
            'rfc'=>'require|max:255',
            'email'=>'require|max:255',
            'address'=>'require|max:255',
            'colony'=>'require|max:255',
            'postalcode'=>'require|max:255',
            'city'=>'require|max:255',
            'state'=>'require|max:255',
            'phone'=>'require|max:255',
            'idstockholder'=>'require|integer'
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
