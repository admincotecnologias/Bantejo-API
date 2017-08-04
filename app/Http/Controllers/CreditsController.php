<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request as Request;
use App;
use Illuminate\Support\Facades\DB;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


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
        $credit = App\approvedcredit::select('credits_approved.*','applications.projectname','clients.businessname','clients.name','clients.lastname')->join('applications','credits_approved.application','=','applications.id')->leftjoin('clients','applications.idclient','=','clients.id')->where('credits_approved.extends',null)->get();
        if(!$credit->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','credits'=>$credit]);
        }
        return response()->json(['error'=>true,'message'=>'no hay creditos registradas.','credits'=>null]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showCreditApproved(Request $request, $id){
        $credit = App\approvedcredit::where('id',$id)->orWhere('extends', $id)->orderBy('start_date', 'asc')->get();
        $application = App\Application::where('id',$credit->toArray()[0]['application'])->first();
        $client = App\Client::where('id',$application->idclient)->first(['businessname','name','lastname']);
        $lastMove = App\controlcredit::select('controlcredits.*')->join('credits_approved','credits_approved.application','=',DB::raw("'".$application->id."'"))->whereRaw('controlcredits.credit=credits_approved.id')->orderBy('controlcredits.period', 'DESC')->first();
        $moves = array();
        foreach ($credit as $data){
            $moves[(string)$data->id]=App\controlcredit::where('credit',$data->id)->get();
        }
        $name = $client->businessname == null ? $client->name." ".$client->lastname : $client->businessname;
        if ($credit->count() > 0) {
                return response()->json(['error'=>false,'message'=>'ok','credits'=>$credit,'project'=>$application->projectname,'client'=>$name,'moves'=>$moves,'lastmove'=>$lastMove]);
            }else {
            return response()->json(['error' => true, 'message' => 'no hay creditos registradas.', 'credits' => null, 'project' => $application->projectname, 'client' => $name, 'moves' => null, 'lastmove' => null]);
        }
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
            $id = $request['application'];
            $application = App\Application::where('id',$id)->find($id);
            $application->status = 'Autorizado';
            if($credit->id != null && $credit->extends==null && $credit->type==1){
                $move = new App\controlcredit();
                $move->credit = $credit->id;
                $move->period = $credit->start_date;
                $move->capital_balance = $credit->amount;
                $move->interest_balance = 0;
                $move->iva_balance = 0;
                $move->interest_arrear_balance = 0;
                $move->interest_arrear_iva_balance = 0;
                $move->currency = $credit->currency;
                $move->save();
            }
            if($credit->extends != null && $credit->type == 2){
                $lastMove = App\controlcredit::where('credit',$credit->extends)->orderBy('id', 'DESC')->first();
                if($lastMove){
                    $startDate = Carbon::parse($credit->start_date);
                    $finalDate = Carbon::parse($credit->start_date);
                    $newDate = Carbon::parse($lastMove->period);
                    $dateDif = $startDate->diffInDays($newDate);
                    $move = new App\controlcredit();
                    $move->credit = $credit->extends;
                    $move->period = $credit->start_date;
                    $move->capital_balance = floatval($lastMove->capital_balance);
                    $move->interest_balance = floatval($lastMove->interest_balance) + (((floatval($credit->interest)/100/365)*floatval($move->capital_balance))*$dateDif);
                    $move->iva_balance = ($move->interest_balance*($credit->iva/100));
                    $move->interest = $move->interest_balance;
                    $move->iva = $move->iva_balance;
                    if($finalDate->addMonth(intval($credit->term))->timestamp <= $newDate->timestamp){
                        $move->interest_arrear_balance = $lastMove->interest_arrear_balance + ((($credit->interest_arrear/100/365)*$dateDif)*($move->capital_balance+$move->interest_balance));
                        $move->interest_arrear_iva_balance = $move->interest_arrear_balance*($credit->iva/100);
                    }else{
                        $move->interest_arrear_balance = 0;
                        $move->interest_arrear_iva_balance = 0;
                    }
                    $move->capital_balance = floatval($credit->amount) + floatval($lastMove->capital_balance);
                    $move->currency = $credit->currency;
                    $move->save();
                }
                else{
                    $move = new App\controlcredit();
                    $move->credit = $credit->extends;
                    $move->period = $credit->start_date;
                    $move->capital_balance = $credit->amount;
                    $move->interest_balance = 0;
                    $move->iva_balance = 0;
                    $move->interest_arrear_balance = 0;
                    $move->interest_arrear_iva_balance = 0;
                    $move->currency = $credit->currency;
                    $move->save();
                }
            }
            $application->save();
            return response()->json(['error'=>false,'message'=>'ok','credit'=>$credit->id,'application'=>$application],200);
        }
    }
    public function addCreditPay(Request $request){
        $validator = Validator::make($request->all(), App\controlcredit::$rules['create']);
        if($validator->fails()){
            return response()->json(['error'=>true,'message'=>'Error de Validaciones.','errors'=>$validator->errors()->all()],200);
        }else{
            $credit = App\controlcredit::create($request->all());
            $credit->save();
            if($credit->capital_balance < .01){
                $status = App\approvedcredit::where('id',$credit->credit)->first();
                App\approvedcredit::where('application',$status->application)->update(['status' => 'Liquidado' ]);

            }
            return response()->json(['error'=>false,'message'=>'ok','credit'=>$credit->id],200);
        }
    }
    public function addPay(Request $request){

    }
}
