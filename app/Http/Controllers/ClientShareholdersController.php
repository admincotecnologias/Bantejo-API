<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\ClientShareholder;
use Validator;

class ClientShareholdersController extends Controller {
	public function all(Request $data)
    {
        $clientshareholders = App\ClientShareholder::get();
        if(!$clientshareholders->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','clientshareholders'=>$clientshareholders]);
        }
        return response()->json(['error'=>true,'message'=>'no hay avals registradas.','clientshareholders'=>null]);
    } 
    public function show($id)
    {
        $clientshareholder = App\ClientShareholder::where('id',$id)->get();
        if(!$clientshareholder->isEmpty())
        {
            $clientshareholder = App\ClientShareholder::where('id',$id)->first();
            return response()->json(['error'=>false,'message'=>'ok','clientshareholder'=>$clientshareholder]);
        }
        return response()->json(['error'=>true,'message'=>'no se encontro aval.','clientshareholder'=>null]);
    } 

    public function showByClient($id)
    {
        $clientshareholder = App\ClientShareholder::where('idclient',$id)->get();
        if(!$clientshareholder->isEmpty())
        {
            $clientshareholder = App\ClientShareholder::where('idclient',$id)->get();
            return response()->json(['error'=>false,'message'=>'ok','clientshareholder'=>$clientshareholder]);
        }
        return response()->json(['error'=>true,'message'=>'no se encontro aval.','clientshareholder'=>null]);
    } 
   

    public function add(Request $data)
    {       
        $validator = Validator::make($data->all(), App\ClientShareholder::$rules['create']);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        else{  
            $clientshareholder = App\ClientShareholder::create($data->all());
            $clientshareholder->save();            
            if($clientshareholder->id>0){
                $clientshareholders = App\ClientShareholder::where('idclient',$clientshareholder->idclient)->get();
                return response()->json(['error'=>false,'message'=>'aval agregado correctamente.','shareholders'=>$clientshareholders]);
            }
        }
    }
    public function delete($id)
    {
        # code...
        $clientshareholder = App\ClientShareholder::where('id', $id)->get();
        if(!$clientshareholder->isEmpty()){
            try {
                $clientshareholder = App\ClientShareholder::where('id', $id)->delete();
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
        $validator = Validator::make($request->all(),App\ClientShareholder::$rules['update'] );
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        $clientshareholder = App\ClientShareholder::where('id',$id)->get();
        if(!$clientshareholder->isEmpty()){
            try {
				$clientshareholder = App\ClientShareholder::where('id',$id)->find($id); 
                $clientshareholder->fill($request->all());
                $clientshareholder->save();
            
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
