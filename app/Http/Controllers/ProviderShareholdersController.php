<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\ProviderShareholder;
use Validator;


class ProviderShareholdersController extends Controller {

	public function all(Request $data)
    {
        $providershareholders = App\ProviderShareholder::get();
        if(!$providershareholders->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','providershareholders'=>$providershareholders]);
        }
        return response()->json(['error'=>true,'message'=>'no hay avales registrados.','providershareholders'=>null]);
    } 
    public function show($id)
    {
        $providershareholder = App\ProviderShareholder::where('id',$id)->get();
        if(!$providershareholder->isEmpty())
        {
            $providershareholder = App\ProviderShareholder::where('id',$id)->first();
            return response()->json(['error'=>false,'message'=>'ok','providershareholder'=>$providershareholder]);
        }
        return response()->json(['error'=>true,'message'=>'no se encontro aval.','providershareholder'=>null]);
    } 
    public function add(Request $data)
    {       
        $validator = Validator::make($data->all(), [
            'name' => 'required|max:255',
			'rfc' => 'required|max:13',
			'participation' => 'required|integer',
			'occupation' => 'required|max:255',
			'oldwork' => 'required|date',
			'idprovider' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        else{  
            $providershareholder = new App\ProviderShareholder;
            $providershareholder->name = $data['name'];
			$providershareholder->rfc = $data['rfc'];
			$providershareholder->participation = $data['participation'];
			$providershareholder->occupation = $data['occupation'];
			$providershareholder->oldwork = $data['oldwork'];
			$providershareholder->idprovider = $data['idprovider'];
            $providershareholder->save();            
            return response()->json(['error'=>false,'message'=>'aval agregado correctamente.','id'=>$providershareholder->id]);
        }
    }
    public function delete($id)
    {
        # code...
        $providershareholder = App\ProviderShareholder::where('id', $id)->get();
        if(!$providershareholder->isEmpty()){
            try {
                $providershareholder = App\ProviderShareholder::where('id', $id)->delete();
                return response()->json(['error'=>false,'message'=>'aval eliminado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>true,'message'=>'no se pudo eliminar aval.','exception'=>$e->getMessage()]);
            }
        }
        return response()->json(['error'=>true,'message'=>'no se encontro aval.']);
    }
    public function update(Request $request,$id)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => 'max:255',
			'rfc' => 'max:13',
			'participation' => 'integer',
			'occupation' => 'max:255',
			'oldwork' => 'date',
			'idprovider' => 'integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        $providershareholder = App\ProviderShareholder::where('id',$id)->get();
        if(!$providershareholder->isEmpty()){
            try {
				$providershareholder = App\ProviderShareholder::where('id',$id)->find($id); 
                if ( $request->has('name') )
                {
                    $providershareholder->name = $request->get('name');
                }  
				if ( $request->has('rfc') )
                {
                    $providershareholder->rfc = $request->get('rfc');
                } 
				if ( $request->has('participation') )
                {
                    $providershareholder->participation = $request->get('participation');
                }   
				if ( $request->has('occupation') )
                {
                    $providershareholder->occupation = $request->get('occupation');
                } 
				if ( $request->has('oldwork') )
                {
                    $providershareholder->oldwork = $request->get('oldwork');
                }  
				if ( $request->has('idprovider') )
                {
                    $providershareholder->idprovider = $request->get('idprovider');
                }                    
                $providershareholder->save();
            
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
