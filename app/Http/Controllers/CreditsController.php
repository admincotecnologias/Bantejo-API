<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request as Request;
use App;
use Illuminate\Support\Facades\DB;
use Validator;
use Carbon\Carbon;


class CreditsController extends Controller {

    public function addCreditType(Request $request){

        $validator = Validator::make($request->all(), App\creditavailable::$rules);
        if($validator->fails()){
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all(),'request'=>$request]);
        }
        $credit = App\creditavailable::create($request->all());
        $credit->save();
        return response()->json(['error'=>false,'message'=>'Credito Creado.','credit'=>$credit->id],200);
    }

    public function allCreditTypes(){
        $credit = App\creditavailable::get();
        if(!$credit->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','credits'=>$credit]);
        }
        return response()->json(['error'=>true,'message'=>'no hay creditos registradas.','credits'=>null]);
    }
    public function allCreditApproved(){
        $credit = App\approvedcredit::select('credits_approved.*','applications.projectname','clients.businessname')->join('applications','credits_approved.application','=','applications.id')->leftjoin('clients','applications.idclient','=','clients.id')->where('credits_approved.extends',null)->get();
        if(!$credit->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','credits'=>$credit]);
        }
        return response()->json(['error'=>true,'message'=>'no hay creditos registradas.','credits'=>null]);
    }
    public function showCreditApproved(Request $request,$id){
        $credit = App\approvedcredit::where('id',$id)->orWhere('extends', $id)->orderBy('start_date', 'asc')->get();
        $application = App\Application::where('id',$credit->toArray()[0]['application'])->first();
        $name = App\Client::where('id',$application->idclient)->first(['businessname']);
        $lastMove = App\controlcredit::select('controlcredits.*')->join('credits_approved','credits_approved.application','=',DB::raw("'".$application->id."'"))->orderBy('controlcredits.period', 'DESC')->first();
        $moves = array();
        foreach ($credit as $data){
            $moves[(string)$data->id]=App\controlcredit::where('credit',$data->id)->get();
        }
        if(!$credit->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','credits'=>$credit,'project'=>$application->projectname,'client'=>$name->businessname,'moves'=>$moves,'lastmove'=>$lastMove]);
        }
        return response()->json(['error'=>true,'message'=>'no hay creditos registradas.','credits'=>null]);
    }
    public function showCreditApprovedByApplication(Request $request,$id){
        $credit = App\approvedcredit::where('application',$id)->where('extends',$id)->get();
        if(!$credit->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','credits'=>$credit]);
        }
        return response()->json(['error'=>true,'message'=>'no hay creditos registradas.','credits'=>null]);
    }
    public function addCreditApproved(Request $request){
        $validator = Validator::make($request->all(), App\approvedcredit::$rules['create']);
        if($validator->fails()){
            return response()->json(['error'=>true,'message'=>'Error de Validaciones.','errors'=>$validator->errors()->all()],200);
        }else{
            $credit = App\approvedcredit::create($request->all());
            $credit->save();
            if($credit->id != null && $credit->extends==null){
                $id = $request['application'];
                $application = App\Application::where('id',$id)->find($id);
                $application->status = 'Autorizado';
                $application->save();
            }
            return response()->json(['error'=>false,'message'=>'ok','credit'=>$credit->id],200);
        }
    }
    public function addCreditPay(Request $request){
        $validator = Validator::make($request->all(), App\controlcredit::$rules['create']);
        if($validator->fails()){
            return response()->json(['error'=>true,'message'=>'Error de Validaciones.','errors'=>$validator->errors()->all()],200);
        }else{
            $credit = App\controlcredit::create($request->all());
            $credit->save();
            return response()->json(['error'=>false,'message'=>'ok','credit'=>$credit->id],200);
        }
    }
    public function addPay(Request $request){

    }
}
