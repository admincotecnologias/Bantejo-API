<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientBank extends Model {
	protected $table = 'client_banks';
	use SoftDeletes;

	protected $fillable = ['accounttype','accountnumber','idclient','idbank','clabe',];

	protected $dates = ['deleted_at',];

    public static $rules = [
	    'create'=>[
            'accounttype' => 'required|max:255',
            'accountnumber' => 'required|max:11',
            'clabe' => 'required|max:20',
            'idclient' => 'required|integer|exists:clients,id',
            'idbank' => 'required|integer|exists:banks,id',
        ],
        'update'=>[
            'accounttype' => 'max:255',
            'accountnumber' => 'required|max:11',
            'clabe' => 'required|max:18',
            'idclient' => 'integer|exists:clients,id',
            'idbank' => 'integer|exists:banks,id',
        ]
    ];
    //Appends
    public $appends = ['namebank'];

    public function getNamebankAttribute(){
        $bank = Bank::where('id',$this->idbank)->first();
        return $bank->name;
    }

	// Relationships

}