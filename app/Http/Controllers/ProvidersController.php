<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Stockholder;
use Validator;


class StockholdersController extends Controller {
	public function all(Request $data)
    {
        $stockholders = Stockholder::get();
        if(!$stockholders->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','stockholders'=>$stockholders]);
        }
        return response()->json(['error'=>true,'message'=>'no hay proveedores registradas.','stockholders'=>null]);
    } 
    public function show($id)
    {
        $stockholder = Stockholder::where('id',$id)->get();
        if(!$stockholder->isEmpty())
        {
            $stockholder = Stockholder::where('id',$id)->first();
            return response()->json(['error'=>false,'message'=>'ok','stockholder'=>$stockholder]);
        }
        return response()->json(['error'=>true,'message'=>'no se encontro proveedor.','stockholder'=>null]);
    } 
    public function add(Request $data)
    {       
        $validator = Validator::make($data->all(), [
            'businessname' => 'required|max:255',
			'rfc' => 'required|max:255',
			'email' => 'required|max:255|email',
			'legalrepresentative' => 'required|max:255',
			'address' => 'required|max:255',
			'colony' => 'required|max:255',
			'postalcode' => 'required|integer',
			'city' => 'required|max:255',
			'state' => 'required|max:255',
			'phone' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        else{  
            $stockholder = new App\Stockholder;
            $stockholder->businessname = $data['businessname'];
			$stockholder->rfc = $data['rfc'];
			$stockholder->email = $data['email'];
			$stockholder->legalrepresentative = $data['legalrepresentative'];
			$stockholder->address = $data['address'];
			$stockholder->colony = $data['colony'];
			$stockholder->postalcode = $data['postalcode'];
			$stockholder->city = $data['city'];
			$stockholder->state = $data['state'];
			$stockholder->phone = $data['phone'];
            $stockholder->save();            
            return response()->json(['error'=>false,'message'=>'proveedor agregado correctamente.','id'=>$stockholder->id]);
        }
    }
    public function delete($id)
    {
        # code...
        $stockholder = App\Stockholder::where('id', $id)->get();
        if(!$stockholder->isEmpty()){
            try {
                $stockholder = App\Stockholder::where('id', $id)->delete();
                return response()->json(['error'=>false,'message'=>'proveedor eliminado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>true,'message'=>'no se pudo eliminar proveedor.','exception'=>$e->getMessage()]);
            }
        }
        return response()->json(['error'=>true,'message'=>'no se encontro proveedor.']);
    }
    public function update(Request $request,$id)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'businessname' => 'max:255',
			'rfc' => 'max:255',
			'email' => 'max:255|email',
			'legalrepresentative' => 'max:255',
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
        $stockholder = App\Stockholder::where('id',$id)->get();
        if(!$stockholder->isEmpty()){
            try {
				$stockholder = App\Stockholder::where('id',$id)->find($id); 
                if ( $request->has('businessname') )
                {
                    $stockholder->businessname = $data['businessname'];
                }
				if ( $request->has('rfc') )
                {
                    $stockholder->rfc = $data['rfc'];
                }
				if ( $request->has('email') )
                {
                    $stockholder->email = $data['email'];
                }
				if ( $request->has('legalrepresentative') )
                {
                    $stockholder->legalrepresentative = $data['legalrepresentative'];
                }
				if ( $request->has('address') )
                {
                    $stockholder->address = $data['address'];
                }
				if ( $request->has('colony') )
                {
                    $stockholder->colony = $data['colony'];
                }
				if ( $request->has('postalcode') )
                {
                    $stockholder->postalcode = $data['postalcode'];
                }
				if ( $request->has('city') )
                {
                    $stockholder->city = $data['city'];
                }
				if ( $request->has('state') )
                {
                    $stockholder->state = $data['state'];
                }
				if ( $request->has('phone') )
                {
                    $stockholder->phone = $data['phone'];
                }                         
                $stockholder->save();
            
                return response()->json(['error'=>false,'message'=>'proveedor editado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>false,'message'=>'proveedor no se pudo actualizar.','errors'=>$e->getMessage()]);
            }
        }   
        else{
            return response()->json(['error'=>false,'message'=>'no se encontro proveedor.']);
        }      
    }
    public function report($id)
    {
        # code...        
        return response()->json(['error'=>false,'message'=>'no se ha definido ningun reporte.']);
    }


}
