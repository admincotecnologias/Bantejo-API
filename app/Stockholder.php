<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\fund;
use Illuminate\Support\Facades\DB;

class Stockholder extends Model {
	use SoftDeletes;

	protected $table = 'stockholder';
	protected $fillable = [
        'businessname',
        'name',
        'lastname',
        'type', //Fisica o Moral
        'rfc',
        'email',
        'legalrepresentative',
        'address',
        'colony',
        'postalcode',
        'city',
        'state',
        'phone',
        'nationality',
    ];


	protected $dates = ['deleted_at'];

    public static $rules = [
        // Validation rules
        'create'=>[
            'businessname'=>'max:255|nullable',
            'name'=>'max:255|nullable',
            'lastname'=>'max:255|nullable',
            'type'=>'required|max:255', //Fisica o Moral
            'rfc'=>'required|max:20',
            'email'=>'required|max:255',
            'address'=>'required|max:255',
            'colony'=>'required|max:255',
            'postalcode'=>'required|max:255',
            'city'=>'required|max:255',
            'state'=>'required|max:255',
            'phone'=>'required|max:255',
            'nationality'=>'required|max:255',
        ],
        'update'=>[
            'businessname'=>'max:255',
            'name'=>'max:255',
            'lastname'=>'max:255',
            'type'=>'max:255', //Fisica o Moral
            'rfc'=>'max:255',
            'email'=>'max:255',
            'legalrepresentative'=>'max:255',
            'address'=>'max:255',
            'colony'=>'max:255',
            'postalcode'=>'max:255',
            'city'=>'max:255',
            'state'=>'max:255',
            'phone'=>'max:255',
            'nationality'=>'max:255',
        ]
    ];

    public $appends = ['capital'];

    //Appends

    public function getCapitalAttribute(){
        $last = fund::where('idstock',$this->id)->get();
        $capital = 0;
        foreach ( $last as $item) {
            $capital += $item->lastmove;
        }
        return $capital;
    }

	// Relationships

}
