<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Application;
use Validator;


class ApplicationsController extends Controller {

    public function all(Request $data)
    {
        $applications = DB::select("select 
                        app.*,
                        cli.businessname
                        from applications as app
                        inner join clients as cli on app.deleted_at <=> NULL and cli.deleted_at <=> NULL and app.idclient = cli.id order by app.id desc;");
        if($applications!=null)
        {
            return response()->json(['error'=>false,'message'=>'ok','applications'=>$applications]);
        }
        return response()->json(['error'=>true,'message'=>'no hay solicitudes registradas.','applications'=>null]);
    } 
    public function show($id)
    {
        $application = Application::where('id',$id)->get();
        $creditaidsSociety = App\Creditaid::where('idapplication',$id)->where('typeguarantee','Moral')->get(['idguarantee']);
        $creditaidsPerson = App\Creditaid::where('idapplication',$id)->where('typeguarantee','Fisica')->get();
        $creditaids = array();
        $Files = App\Files::where('idapplication',$id)->get(['name','id']);
        if(!$creditaidsSociety->isEmpty())
        {
            foreach ($creditaidsSociety as &$valor) {
                $creditaids[] = App\Client::where('id',$valor->idguarantee)->first();
            }
        }
        foreach ($creditaidsPerson as &$valor) {
                $creditaids[] = $valor;
            }
        unset($valor);
        if(!$application->isEmpty())
        {
            $application = Application::where('id',$id)->first();
            return response()->json(['error'=>false,'message'=>'ok','application'=>$application,'creditaids'=>$creditaids,'files'=>$Files]);
        }
        return response()->json(['error'=>true,'message'=>'no se encontro solocitud.','application'=>null,'creditaids'=>null,'files'=>null]);
    } 
    public function add(Request $request)
    {       
        $validator = Validator::make($request->all(), [
            'amountrequest' => 'required|numeric',
            'applicationdate'=> 'required|date',
            'place'=> 'required|max:255',
            'creditterm'=> 'required|numeric',
            'projectname'=> 'required|max:255',
            'status'=> 'required|max:255',
            'idclient'=> 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        else{  
            $application = new App\Application;
            $application->amountrequest = $request->get('amountrequest');
            $application->applicationdate = $request->get('applicationdate');
            $application->place = $request->get('place');
            $application->creditterm = $request->get('creditterm');
            $application->projectname = $request->get('projectname');
            $application->status = $request->get('status');
            $application->idclient = $request->get('idclient');
            $application->save();            
            return response()->json(['error'=>false,'message'=>'solocitud agregado correctamente.','id'=>$application->id]);
        }
    }
    public function delete($id)
    {
        # code...
        $application = App\Application::where('id', $id)->get();
        if(!$application->isEmpty()){
            try {
                $application = App\Application::where('id', $id)->delete();
                return response()->json(['error'=>false,'message'=>'solocitud eliminado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>true,'message'=>'no se pudo eliminar solocitud.','exception'=>$e->getMessage()]);
            }
        }
        return response()->json(['error'=>true,'message'=>'no se encontro solocitud.']);
    }
    public function update(Request $request,$id)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'amountrequest' => 'numeric',
            'amountsuccess'=> 'numeric',
            'applicationdate'=> 'date',
            'term'=> 'numeric',
            'rate'=> 'numeric',
            'arrears'=> 'numeric',
            'periodicity'=> 'numeric',
            'graceperiod'=> 'numeric',
            'tax'=> 'numeric',
            'interesttax'=> 'numeric',
            'interest'=> 'numeric',
            'status'=> 'max:255'
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        $application = App\Application::where('id',$id)->get();
        if(!$application->isEmpty()){
            try {
				$application = App\Application::where('id',$id)->find($id);
                if ( $request->has('amountrequest') )
                {
                    $application->amountrequest = $request->get('amountrequest');
                }  
                if ( $request->has('amountsuccess') )
                {
                    $application->amountsuccess = $request->get('amountsuccess');
                } 
                if ( $request->has('applicationdate') )
                {
                    $application->applicationdate = $request->get('applicationdate');
                }  
                if ( $request->has('term') )
                {
                    $application->term = $request->get('term');
                } 
                if ( $request->has('rate') )
                {
                    $application->rate = $request->get('rate');
                } 
                if ( $request->has('arrears') )
                {
                    $application->arrears = $request->get('arrears');
                }
                if ( $request->has('periodicity') )
                {
                    $application->periodicity = $request->get('periodicity');
                }
                if ( $request->has('graceperiod') )
                {
                    $application->graceperiod = $request->get('graceperiod');
                }
                if ( $request->has('tax') )
                {
                    $application->tax = $request->get('tax');
                }
                if ( $request->has('interesttax') )
                {
                    $application->interesttax = $request->get('interesttax');
                }
                if ( $request->has('interest') )
                {
                    $application->interest = $request->get('interest');
                }
                if ( $request->has('status') )
                {
                    $application->status = $request->get('status');
                }                      
                $application->save();
            
                return response()->json(['error'=>false,'message'=>'solocitud editado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>false,'message'=>'solocitud no se pudo actualizar.','errors'=>$e->getMessage()]);
            }
        }   
        else{
            return response()->json(['error'=>false,'message'=>'no se encontro solocitud.']);
        }      
    }
    public function report($id)
    {
        # code...        
        return response()->json(['error'=>false,'message'=>'no se ha definido ningun reporte.']);
    }	

}
