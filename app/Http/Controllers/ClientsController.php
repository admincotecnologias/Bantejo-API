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
            return response()->json(['error'=>false,'message'=>'ok','client'=>$client,'banks'=>$clientbank,'shareholders'=>$clientshareholder,'files'=>$clientFiles,'managers'=>$clientsmanagers]);
        }
        return response()->json(['error'=>true,'message'=>'no se encontro cliente.','client'=>null]);
    } 
    public function add(Request $data)
    {       
        $validator = Validator::make($data->all(), [
            'businessname' => 'required|max:255',
			'employeenumber' => 'integer',
			'rfc' => 'required|max:255',
			'fiel' => 'max:255',
			'email' => 'required|max:255|email',
			'businesscategory' => 'required|max:255',
			'constitutiondate' => 'required|date',
			'address' => 'required|max:255',
			'colony' => 'required|max:255',
			'postalcode' => 'required|integer',
			'city' => 'required|max:255',
			'state' => 'required|max:255',
			'phone' => 'required|max:20',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        else{  
            $client = new App\Client;
            $client->businessname = $data['businessname'];
			$client->employeenumber = $data['employeenumber'];
			$client->rfc = $data['rfc'];
			$client->fiel = $data['fiel'];
			$client->email = $data['email'];
			$client->businesscategory = $data['businesscategory'];
			$client->constitutiondate = Carbon::parse($data['constitutiondate']);
			$client->address = $data['address'];
			$client->colony = $data['colony'];
			$client->postalcode = $data['postalcode'];
			$client->city = $data['city'];
			$client->state = $data['state'];
			$client->phone = $data['phone'];
            $client->save();            
            return response()->json(['error'=>false,'message'=>'cliente agregado correctamente.','id'=>$client->id]);
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
            } catch (Exception $e) {
                return response()->json(['error'=>true,'message'=>'no se pudo eliminar cliente.','exception'=>$e->getMessage()]);
            }
        }
        return response()->json(['error'=>true,'message'=>'no se encontro cliente.']);
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
        $client = App\Client::where('id',$id)->get();
        if(!$client->isEmpty()){
            try {
				$client = App\Client::where('id',$id)->find($id); 
                if ( $data->has('businessname') )
                {
                    $client->businessname = $data['businessname'];
                }
				if ( $data->has('employeenumber') )
                {
                    $client->employeenumber = $data['employeenumber'];
                }
				if ( $data->has('rfc') )
                {
                    $client->rfc = $data['rfc'];
                }
				if ( $data->has('fiel') )
                {
                    $client->fiel = $data['fiel'];
                }
				if ( $data->has('email') )
                {
                    $client->email = $data['email'];
                }
				if ( $data->has('businesscategory') )
                {
                    $client->businesscategory = $data['businesscategory'];
                }
				if ( $data->has('constitutiondate') )
                {
                    $client->constitutiondate = Carbon::parse($data['constitutiondate']);
                }
				if ( $data->has('address') )
                {
                    $client->address = $data['address'];
                }
				if ( $data->has('colony') )
                {
                    $client->colony = $data['colony'];
                }
				if ( $data->has('postalcode') )
                {
                    $client->postalcode = $data['postalcode'];
                }
				if ( $data->has('city') )
                {
                    $client->city = $data['city'];
                }
				if ( $data->has('state') )
                {
                    $client->state = $data['state'];
                }
				if ( $data->has('phone') )
                {
                    $client->phone = $data['phone'];
                }                         
                $client->save();
            
                return response()->json(['error'=>false,'message'=>'cliente editado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>false,'message'=>'cliente no se pudo actualizar.','errors'=>$e->getMessage()]);
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
