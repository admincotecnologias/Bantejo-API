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
        $validator = Validator::make($request->all(), App\Application::$rules['create']);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        else{  
            $application = App\Application::create($request->all());
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
        $validator = Validator::make($request->all(), App\Application::$rules['update']);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        $application = App\Application::where('id',$id)->get();
        if(!$application->isEmpty()){
            try {
				$application = App\Application::where('id',$id)->find($id);
                $application->fill($request->all());
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
