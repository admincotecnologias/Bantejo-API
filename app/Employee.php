<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model {
	use SoftDeletes;

	protected $fillable = ['name','lastname','iduser','idoccupation',];

	protected $hidden = [
        'deleted_at', 
    ];
	protected $dates = ['deleted_at',];

    public static $rules = [
        'create'=>[
            'name' => 'required|max:255',
            'lastname' => 'required|max:255',
            'iduser' => 'required|integer|unique:employees',
            'idoccupation' => 'required|integer',
        ],
        'update'=>[
            'name' => 'max:255',
            'lastname' => 'max:255',
            'iduser' => 'integer|unique:employees',
            'idoccupation' => 'integer',
        ]
    ];
}
