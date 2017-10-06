<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\ClientBank;
use Validator;


class ClientBanksController extends Controller {

	public function all(Request $data)
    {
        $clientbanks = ClientBank::get();
        if(!$clientbanks->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','clientbanks'=>$clientbanks]);
        }
        return response()->json(['error'=>true,'message'=>'no hay bancos registradas.','clientbanks'=>null]);
    } 

    public function showByClient($id)
    {
        $clientbanks = ClientBank::where('idclient', $id)->get();
        if(!$clientbanks->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','clientbanks'=>$clientbanks]);
        }
        return response()->json(['error'=>true,'message'=>'no hay bancos registradas.','clientbanks'=>null]);
    } 
    
    public function show($id)
    {
        $clientbank = ClientBank::where('id',$id)->get();
        if(!$clientbank->isEmpty())
        {
            $clientbank = ClientBank::where('id',$id)->first();            
            return response()->json(['error'=>false,'message'=>'ok','clientbank'=>$clientbank]);
        }
        return response()->json(['error'=>true,'message'=>'no se encontro banco.','clientbank'=>null]);
    } 
    public function add(Request $data)
    {       
        $validator = Validator::make($data->all(), App\ClientBank::$rules['create']);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        else{  
            $clientbank = App\ClientBank::create($data->all());
            $clientbank->save();
            if($clientbank->id>0) {
                $clientbanks = App\ClientBank::where('idclient', $clientbank->id)->get();
                return response()->json(['error' => false, 'message' => 'banco agregado correctamente.', 'accounts' => $clientbanks]);
            }
                return response()->json(['error'=>true,'message'=>'banco no se agrego correctamente.','accounts'=>null]);            }
        }
    public function delete($id)
    {
        # code...
        $clientbank = App\ClientBank::where('id', $id)->first();
        if($clientbank->id != null){
            try {
                $idclient = $clientbank->idclient;
                $clientbank = App\ClientBank::where('id', $id)->delete();
                if($clientbank>0){
                    $banks = App\ClientBank::where('idclient', $idclient)->get();
                    return response()->json(['error'=>false,'message'=>'banco eliminado correctamente.','accounts'=>$banks]);
                }else{
                    return response()->json(['error'=>true,'message'=>'no se pudo eliminar banco.']);
                }
            } catch (\Exception $e) {
                return response()->json(['error'=>true,'message'=>'no se pudo eliminar banco.','exception'=>$e->getMessage()]);
            }
        }
        return response()->json(['error'=>true,'message'=>'no se encontro banco.']);
    }
    public function update(Request $request,$id)
    {
        # code...
        $validator = Validator::make($request->all(), App\ClientBank::$rules['update'] );
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        $clientbank = App\ClientBank::where('id',$id)->get();
        if(!$clientbank->isEmpty()){
            try {
				$clientbank = App\ClientBank::where('id',$id)->find($id); 
                $clientbank->fill($request->all());
                $clientbank->save();
            
                return response()->json(['error'=>false,'message'=>'banco editado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>false,'message'=>'banco no se pudo actualizar.','errors'=>$e->getMessage()]);
            }
        }   
        else{
            return response()->json(['error'=>false,'message'=>'no se encontro banco.']);
        }      
    }
    public function report($id)
    {
        # code...        
        return response()->json(['error'=>false,'message'=>'no se ha definido ningun reporte.']);
    }

}
