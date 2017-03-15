<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request as Request;
use App;
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
            return response()->json(['error'=>false,'message'=>'ok','$credits'=>$credit]);
        }
        return response()->json(['error'=>true,'message'=>'no hay creditos registradas.','$credits'=>null]);
    }
    public function allCreditApproved(){
        $credit = App\approvedcredit::get();
        if(!$credit->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','$credits'=>$credit]);
        }
        return response()->json(['error'=>true,'message'=>'no hay creditos registradas.','$credits'=>null]);
    }
    public function showCreditApproved(Request $request,$id){
        $credit = App\approvedcredit::where('id',$id)->get();
        if(!$credit->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','$credits'=>$credit]);
        }
        return response()->json(['error'=>true,'message'=>'no hay creditos registradas.','$credits'=>null]);
    }
    public function showCreditApprovedByApplication(Request $request,$id){
        $credit = App\approvedcredit::where('application',$id)->get();
        if(!$credit->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','$credits'=>$credit]);
        }
        return response()->json(['error'=>true,'message'=>'no hay creditos registradas.','$credits'=>null]);
    }
    public function addCreditApproved(Request $request){
        $validator = Validator::make($request->all(), App\approvedcredit::$rules['create']);
        if($validator->fails()){
            return response()->json(['error'=>true,'message'=>'Error de Validaciones.','errors'=>$validator->errors()->all()],200);
        }else{
            $credit = App\approvedcredit::create($request->all());
            $credit->save();
            if($credit->id != null){
                $id = $request['application'];
                $application = App\Application::where('id',$id)->find($id);
                $application->status = 'Autorizado';
                $application->save();
            }
            return response()->json(['error'=>false,'message'=>'ok','credit'=>$credit->id],200);
        }
    }
}
