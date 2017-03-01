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
        $validator = Validator::make($data->all(), [
            'accounttype' => 'required|max:255',
			'accountnumber' => 'required|max:11',
            'clabe' => 'required|max:18',
			'idclient' => 'required|integer',
			'idbank' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        else{  
            $clientbank = new App\ClientBank;
            $clientbank->accounttype = $data['accounttype'];
			$clientbank->accountnumber = $data['accountnumber'];
            $clientbank->clabe = $data['clabe'];
			$clientbank->idclient = $data['idclient'];
			$clientbank->idbank = $data['idbank'];
            $clientbank->save();            
            return response()->json(['error'=>false,'message'=>'banco agregado correctamente.','id'=>$clientbank->id]);
        }
    }
    public function delete($id)
    {
        # code...
        $clientbank = App\ClientBank::where('id', $id)->get();
        if(!$clientbank->isEmpty()){
            try {
                $clientbank = App\ClientBank::where('id', $id)->delete();
                return response()->json(['error'=>false,'message'=>'banco eliminado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>true,'message'=>'no se pudo eliminar banco.','exception'=>$e->getMessage()]);
            }
        }
        return response()->json(['error'=>true,'message'=>'no se encontro banco.']);
    }
    public function update(Request $request,$id)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'accounttype' => 'max:255',
			'accountnumber' => 'required|max:11',
            'clabe' => 'required|max:18',
			'idclient' => 'integer',
			'idbank' => 'integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        $clientbank = App\ClientBank::where('id',$id)->get();
        if(!$clientbank->isEmpty()){
            try {
				$clientbank = App\ClientBank::where('id',$id)->find($id); 
                if ( $request->has('accounttype') )
                {
                    $clientbank->accounttype = $request->get('accounttype');
                }  
				if ( $request->has('accountnumber') )
                {
                    $clientbank->accountnumber = $request->get('accountnumber');
                } 
                if ( $request->has('clabe') )
                {
                    $clientbank->clabe = $request->get('clabe');
                }  
				if ( $request->has('idclient') )
                {
                    $clientbank->idclient = $request->get('idclient');
                } 
				if ( $request->has('idbank') )
                {
                    $clientbank->idbank = $request->get('idbank');
                }                      
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
