<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Creditaid;
use Validator;


class CreditaidsController extends Controller {

	public function all(Request $data)
    {
        $creditaids = Creditaid::get();
        if(!$creditaids->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','creditaids'=>$creditaids]);
        }
        return response()->json(['error'=>true,'message'=>'no hay avales registradas.','creditaids'=>null]);
    } 
    public function show($id)
    {
        $creditaid = Creditaid::where('id',$id)->get();
        if(!$creditaid->isEmpty())
        {
            $creditaid = Creditaid::where('id',$id)->first();
            $creditaidbank = App\CreditaidBank::where('idcreditaid',$creditaid->id)->get();
            $creditaidshareholder = App\Creditaidshareholder::where('idcreditaid',$creditaid->id)->get();
            return response()->json(['error'=>false,'message'=>'ok','creditaid'=>$creditaid,'banks'=>$creditaidbank,'shareholders'=>$creditaidshareholder]);
        }
        return response()->json(['error'=>true,'message'=>'no se encontro aval.','creditaid'=>null]);
    } 
    public function add(Request $data)
    {      
        if ( $data->has('typeguarantee') )
        {
            if($data->input('typeguarantee')=='Moral'){
                $validator = Validator::make($data->all(), App\Creditaid::$rules['moral']['create']);
                    if ($validator->fails()) {
                    return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
                    }
                    else{  
                        $creditaid =  App\Creditaid::create($data->all());
                        $creditaid->save();            
                        return response()->json(['error'=>false,'message'=>'aval agregado correctamente.','id'=>$creditaid->id]);
                    }
            }else{
                $validator = Validator::make($data->all(), App\Creditaid::$rules['fisica']['create']);
                if ($validator->fails()) {
                    return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
                }
                else{
                    $creditaid =  App\Creditaid::create($data->all());
                    $creditaid->save();            
                    return response()->json(['error'=>false,'message'=>'aval agregado correctamente.','id'=>$creditaid->id]);
                }
            }
        }
    }
    public function delete($id)
    {
        # code...
        $creditaid = App\Creditaid::where('id', $id)->get();
        if(!$creditaid->isEmpty()){
            try {
                $creditaid = App\Creditaid::where('id', $id)->delete();
                return response()->json(['error'=>false,'message'=>'aval eliminado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>true,'message'=>'no se pudo eliminar aval.','exception'=>$e->getMessage()]);
            }
        }
        return response()->json(['error'=>true,'message'=>'no se encontro aval.']);
    }
    public function update(Request $data,$id)
    {
        # code...
        $validator = Validator::make($data->all(), [
            'businessname' => 'max:255',
			'employeenumber' => 'integer',
			'rfc' => 'max:255',
			'fiel' => 'max:255',
			'email' => 'max:255|email',
			'businesscategory' => 'max:255',
			'constitutiondate' => 'date',
            'legalrepresentativename' => 'max:255',
			'legalrepresentativelastname' => 'max:255',
			'address' => 'max:255',
			'colony' => 'max:255',
			'postalcode' => 'integer',
			'city' => 'max:255',
			'state' => 'max:255',
			'phone' => 'integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        $creditaid = App\Creditaid::where('id',$id)->get();
        if(!$creditaid->isEmpty()){
            try {
				$creditaid = App\Creditaid::where('id',$id)->find($id); 
                if ( $data->has('businessname') )
                {
                    $creditaid->businessname = $data['businessname'];
                }
				if ( $data->has('employeenumber') )
                {
                    $creditaid->employeenumber = $data['employeenumber'];
                }
				if ( $data->has('rfc') )
                {
                    $creditaid->rfc = $data['rfc'];
                }
				if ( $data->has('fiel') )
                {
                    $creditaid->fiel = $data['fiel'];
                }
				if ( $data->has('email') )
                {
                    $creditaid->email = $data['email'];
                }
				if ( $data->has('businesscategory') )
                {
                    $creditaid->businesscategory = $data['businesscategory'];
                }
				if ( $data->has('constitutiondate') )
                {
                    $creditaid->constitutiondate = Carbon::parse($data['constitutiondate']);
                }
				if ( $data->has('legalrepresentativename') )
                {
                    $creditaid->legalrepresentativename = $data['legalrepresentativename'];
                }
                if ( $data->has('legalrepresentativelastname') )
                {
                    $creditaid->legalrepresentativelastname = $data['legalrepresentativelastname'];
                }
				if ( $data->has('address') )
                {
                    $creditaid->address = $data['address'];
                }
				if ( $data->has('colony') )
                {
                    $creditaid->colony = $data['colony'];
                }
				if ( $data->has('postalcode') )
                {
                    $creditaid->postalcode = $data['postalcode'];
                }
				if ( $data->has('city') )
                {
                    $creditaid->city = $data['city'];
                }
				if ( $data->has('state') )
                {
                    $creditaid->state = $data['state'];
                }
				if ( $data->has('phone') )
                {
                    $creditaid->phone = $data['phone'];
                }                         
                $creditaid->save();
            
                return response()->json(['error'=>false,'message'=>'aval editado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>false,'message'=>'aval no se pudo actualizar.','errors'=>$e->getMessage()]);
            }
        }   
        else{
            return response()->json(['error'=>false,'message'=>'no se encontro aval.']);
        }      
    }
    public function report($id)
    {
        # code...        
        return response()->json(['error'=>false,'message'=>'no se ha definido ningun reporte.']);
    }

}
