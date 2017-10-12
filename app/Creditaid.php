<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Creditaid extends Model {
    protected $table = 'creditaids';
    use SoftDeletes;
	protected $fillable = [
	    'idapplication',
        'name',
        'lastname',
        'business_name',
        'rfc',
        'curp',
        'birthday',
        'country',
        'nacionality',
        'email',
        'fiel',
        'address',
        'phone',
        'maritalstatus',
        'regimen',
        'relationship',
        'companyjob',
        'phonejob',
        'occupation',
        'oldwork',
        'idguarantee',
        'typeguarantee',];

	protected $hidden = [
        'deleted_at', 
    ];
	protected $dates = ['deleted_at',];

	public static $rules=[
	    'moral'=>[
	        'create'=>[
                'idapplication' => 'required|integer|exists:applications,id',
                'typeguarantee'=> 'required|max:255',
                'idguarantee'=> 'nullable|integer',
                'business_name' => 'required|max:255',
                'rfc'=> 'required|max:13|unique:creditaids|unique:clients',
                'birthday'=> 'required|date',
                'country'=> 'required|max:255',
                'nacionality'=> 'required|max:255',
                'email'=> 'required|email|max:255',
                'fiel'=> 'max:255',
                'address'=> 'required|max:255',
                'phone'=> 'required|max:255',
                'occupation'=> 'nullable|max:255',
                'oldwork'=> 'nullable|date',
                'idfile'=> 'nullable|integer',
            ],
            'update'=>[
                'idapplication' => 'required|integer|exists:applications,id',
                'typeguarantee'=> 'required|max:255',
                'idguarantee'=> 'nullable|integer',
                'business_name' => 'required|max:255',
                'birthday'=> 'required|date',
                'country'=> 'required|max:255',
                'nacionality'=> 'required|max:255',
                'email'=> 'required|email|max:255',
                'fiel'=> 'max:255',
                'address'=> 'required|max:255',
                'phone'=> 'required|max:255',
                'occupation'=> 'nullable|max:255',
                'oldwork'=> 'nullable|date',
                'idfile'=> 'nullable|integer',
            ]
        ],
        'fisica'=>[
            'create'=>[
                'idapplication' => 'required|integer|exists:applications,id',
                'name'=> 'required|max:255',
                'lastname'=> 'required|max:255',
                'rfc'=> 'required|max:13|unique:creditaids|unique:clients',
                'curp'=> 'required|max:255',
                'birthday'=> 'required|date',
                'country'=> 'required|max:255',
                'nacionality'=> 'required|max:255',
                'email'=> 'required|email|max:255',
                'fiel'=> 'max:255',
                'address'=> 'required|max:255',
                'phone'=> 'required|max:255',
                'maritalstatus'=> 'required|max:255',
                'regimen'=> 'nullable|max:255',
                'relationship'=> 'required|max:255',
                'companyjob'=> 'required|max:255',
                'phonejob'=> 'required|max:255',
                'occupation'=> 'required|max:255',
                'oldwork'=> 'required|date',
                'typeguarantee'=> 'required|max:255',
                'idguarantee'=> 'nullable|required|integer',
                'idfile'=> 'nullable|required|integer',
            ],
            'update'=>[
                'idapplication' => 'required|integer|exists:applications,id',
                'name'=> 'required|max:255',
                'lastname'=> 'required|max:255',
                'rfc'=> 'required|max:13|unique:creditaids|unique:clients',
                'curp'=> 'required|max:255',
                'birthday'=> 'required|date',
                'country'=> 'required|max:255',
                'nacionality'=> 'required|max:255',
                'email'=> 'required|email|max:255',
                'fiel'=> 'max:255',
                'address'=> 'required|max:255',
                'phone'=> 'required|max:255',
                'maritalstatus'=> 'required|max:255',
                'regimen'=> 'nullable|max:255',
                'relationship'=> 'required|max:255',
                'companyjob'=> 'required|max:255',
                'phonejob'=> 'required|max:255',
                'occupation'=> 'required|max:255',
                'oldwork'=> 'required|date',
                'typeguarantee'=> 'required|max:255',
                'idguarantee'=> 'nullable|required|integer',
                'idfile'=> 'nullable|required|integer',
            ],
        ]
    ];
	protected $appends = ['businessname','rfcmoral'];
	public function getBusinessnameAttribute(){
	    if($this->idguarantee!=null){
	        $client = Client::where('id',$this->idguarantee)->first(['businessname']);
	        return $client->businessname;
        }
        else{
	        return null;
        }
    }
    public function getRfcmoralAttribute(){
        if($this->idguarantee!=null){
            $client = Client::where('id',$this->idguarantee)->first(['rfc']);
            return $client->rfc;
        }
        else{
            return null;
        }
    }

}
