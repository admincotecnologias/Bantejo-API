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
    public function add(Request $data)
    {       
        $validator = Validator::make($data->all(), [
            'name' => 'required|max:255',
            'lastname' => 'required|max:255',
			'rfc' => 'required|max:13',
			'participation' => 'required|integer',
			'oldwork' => 'required|date',
			'idclient' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        else{  
            $clientshareholder = new App\ClientShareholder;
            $clientshareholder->name = $data['name'];
            $clientshareholder->lastname = $data['lastname'];
			$clientshareholder->rfc = $data['rfc'];
			$clientshareholder->participation = $data['participation'];
			$clientshareholder->oldwork = $data['oldwork'];
			$clientshareholder->idclient = $data['idclient'];
            $clientshareholder->save();            
            return response()->json(['error'=>false,'message'=>'aval agregado correctamente.','id'=>$clientshareholder->id]);
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
        $validator = Validator::make($request->all(), [
            'name' => 'max:255',
            'lastname' => 'max:255',
			'rfc' => 'max:13',
			'participation' => 'integer',
			'oldwork' => 'date',
			'idclient' => 'integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        $clientshareholder = App\ClientShareholder::where('id',$id)->get();
        if(!$clientshareholder->isEmpty()){
            try {
				$clientshareholder = App\ClientShareholder::where('id',$id)->find($id); 
                if ( $request->has('name') )
                {
                    $clientshareholder->name = $request->get('name');
                } 
                if ( $request->has('lastname') )
                {
                    $clientshareholder->lastname = $request->get('lastname');
                } 
				if ( $request->has('rfc') )
                {
                    $clientshareholder->rfc = $request->get('rfc');
                } 
				if ( $request->has('participation') )
                {
                    $clientshareholder->participation = $request->get('participation');
                } 
				if ( $request->has('oldwork') )
                {
                    $clientshareholder->oldwork = $request->get('oldwork');
                }  
				if ( $request->has('idclient') )
                {
                    $clientshareholder->idclient = $request->get('idclient');
                }                    
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
