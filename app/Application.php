<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model {
	use SoftDeletes;

	protected $fillable = [
        'amountrequest',
        'applicationdate',
        'place',
        'creditterm',
        'projectname',
        'status',
        'idclient',
    ];

	protected $dates = ['deleted_at',];

    public static $rules = [
        'create'=>[
            'amountrequest' => 'required|numeric',
            'applicationdate'=> 'required|date',
            'place'=> 'required|max:255',
            'creditterm'=> 'required|numeric',
            'projectname'=> 'required|max:255',
            'status'=> 'required|max:255',
            'idclient'=> 'required|integer',
        ],
        'update'=>[
            'amountrequest' => 'numeric',
            'applicationdate'=> 'date',
            'place'=> 'max:255',
            'creditterm'=> 'numeric',
            'projectname'=> 'max:255',
            'status'=> 'max:255',
            'idclient'=> 'integer',
        ]
    ];
    protected $appends = ['clientname','avalesnum','filesnum'];

    public function getClientnameAttribute(){
        $client = Client::withTrashed()->where('id',$this->idclient)->first();
        return $client->type=="Moral"?$client->businessname:$client->name.' '.$client->lastname;
    }
    public function getAvalesnumAttribute(){
        return Creditaid::where('idapplication',$this->id)->count();
    }
    public function getFilesnumAttribute(){
        return Files::where('idapplication',$this->id)->count();
    }
}
