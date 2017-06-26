<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Client;
use Validator;
use PDF;

class ClientsController extends Controller {
    public function returnPdf(Request $request){
        $item = new \stdClass();
        $item->periodo=Carbon::now();
        $item->interes='10%';
        $item->iva='16%';
        $item->saldocapital=10000;
        $item->saldointeres=10000;
        $item->saldoiva=10000;
        for ($i=0;$i<24;$i++){
            $item->data[]=$item;
        }
        //return view('prueba',array('data'=>$item->data));
        $pdf = PDF::loadView('prueba',array('data'=>$item->data))->setOptions(['defaultFont' => 'Helvetica']);
        return $pdf->download('invoice.pdf');
    }
	public function all(Request $data)
    {
        $clients = Client::get();
        if(!$clients->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','clients'=>$clients]);
        }
        return response()->json(['error'=>true,'message'=>'no hay clientes registradas.','clients'=>null]);
    } 
    public function show($id)
    {
        $client = Client::where('id',$id)->get();
        if(!$client->isEmpty())
        {
            $client = Client::where('id',$id)->first();
            $clientbank = App\ClientBank::where('idclient',$client->id)->get();
            $clientshareholder = App\ClientShareholder::where('idclient',$client->id)->get();
            $clientFiles = App\FileClient::where('idclient',$client->id)->get();
            $clientsmanagers = App\managerclient::where('idclient',$client->id)->get();
            return response()->json([
                'error'=>false,
                'message'=>'ok',
                'client'=>$client,
                'banks'=>$clientbank,
                'shareholders'=>$clientshareholder,
                'files'=>$clientFiles,
                'managers'=>$clientsmanagers
            ]);
        }
        return response()->json(['error'=>true,'message'=>'no se encontro cliente.','client'=>null]);
    } 
    public function add(Request $data)
    {       
        $validator = Validator::make($data->all(), App\Client::$rules['create']);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        else{  
            $client = App\Client::create($data->all());
            $client->save();            
            return response()->json(['error'=>false,'message'=>'cliente agregado correctamente.','client'=>$client]);
        }
    }
    public function delete($id)
    {
        # code...
        $client = App\Client::where('id', $id)->get();
        if(!$client->isEmpty()){
            try {
                $client = App\Client::where('id', $id)->delete();
                return response()->json(['error'=>false,'message'=>'cliente eliminado correctamente.']);
            } catch (\Exception $e) {
                return response()->json(['error'=>true,'message'=>'no se pudo eliminar cliente.','exception'=>$e->getMessage()]);
            }
        }
        return response()->json(['error'=>true,'message'=>'no se encontro cliente.']);
    }
    public function update(Request $data,$id)
    {
        # code...
        $validator = Validator::make($data->all(), App\Client::$rules['update']);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        $client = App\Client::where('id',$id)->get();
        if(!$client->isEmpty()){
            try {
				$client = App\Client::where('id',$id)->find($id); 
                $client->fill($data->all());
                $client->save();
            
                return response()->json(['error'=>false,'message'=>'cliente editado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>true,'message'=>'cliente no se pudo actualizar.','errors'=>$e->getMessage()]);
            }
        }   
        else{
            return response()->json(['error'=>false,'message'=>'no se encontro cliente.']);
        }      
    }
    public function report($id)
    {
        # code...        
        return response()->json(['error'=>false,'message'=>'no se ha definido ningun reporte.']);
    }

}
