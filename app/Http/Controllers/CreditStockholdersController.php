<?php namespace App\Http\Controllers;
use Illuminate\Http\Request as Request;
use App;
use Illuminate\Support\Facades\DB;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class CreditStockholdersController extends Controller {

    private function addCtrlPayZero(App\fund $fund){
        if($fund->extends != null){
            $lastMove = App\Control_Fund::where('credit',$fund->extends)->orderBy('id', 'DESC')->first();
            if($lastMove){
                $startDate = Carbon::parse($fund->start_date);
                $finalDate = Carbon::parse($fund->start_date);
                $newDate = Carbon::parse($lastMove->period);
                $dateDif = $startDate->diffInDays($newDate);
                $move = new App\Control_Fund();
                $move->credit = $fund->extends;
                $move->period = $fund->start_date;
                $move->capital_balance = floatval($lastMove->capital_balance);
                $move->interest_balance = floatval($lastMove->interest_balance) + (((floatval($fund->interest)/100/365)*floatval($move->capital_balance))*$dateDif);
                $move->iva_balance = ($move->interest_balance*($fund->iva/100));
                if($finalDate->addMonth(intval($fund->term))->timestamp <= $newDate->timestamp){
                    $move->interest_arrear_balance = $lastMove->interest_arrear_balance + ((($fund->interest_arrear/100/365)*$dateDif)*($move->capital_balance+$move->interest_balance));
                    $move->interest_arrear_iva_balance = $move->interest_arrear_balance*($fund->iva/100);
                }else{
                    $move->interest_arrear_balance = 0;
                    $move->interest_arrear_iva_balance = 0;
                }
                $move->capital_balance = floatval($fund->amount) + floatval($lastMove->capital_balance);
                $move->currency = $fund->currency;
                $move->save();
            }
            else{
                $move = new App\Control_Fund();
                $move->credit = $fund->extends;
                $move->period = $fund->start_date;
                $move->capital_balance = $fund->amount;
                $move->interest_balance = 0;
                $move->iva_balance = 0;
                $move->interest_arrear_balance = 0;
                $move->interest_arrear_iva_balance = 0;
                $move->currency = $fund->currency;
                $move->save();
            }
        }
    }

	public function CreateFund (Request $request){
	    $validate = Validator::make($request->all(),App\fund::$rules['create']);
	    if($validate->fails()){
            return response()->json(['error'=>true,
                'message'=>'error al validar campos.',
                'errors'=>$validate->errors()->all()]);
        }else{
	        $fund = App\fund::create($request->all());
	        $fund->save();
	        if($fund->id>0){
	            $this->addCtrlPayZero($fund);
                return response()->json(['error'=>false,
                    'message'=>'Creado.',
                    'fund'=>$fund->id]);
            }else{
                return response()->json(['error'=>true,
                    'message'=>'Error al crear.',
                    'fund'=>null]);
            }
        }
    }
    public function CreateCntrl (Request $request){
        $validate = Validator::make($request->all(),App\Control_Fund::$rules['create']);
        if($validate->fails()){
            return response()->json(['error'=>true,
                'message'=>'error al validar campos.',
                'errors'=>$validate->errors()->all()]);
        }else{
            $fund = App\Control_Fund::create($request->all());
            $fund->save();
            if($fund->id>0){
                return response()->json(['error'=>false,
                    'message'=>'Creado.',
                    'fund'=>$fund->id]);
            }else{
                return response()->json(['error'=>true,
                    'message'=>'Error al crear.',
                    'fund'=>null]);
            }
        }
    }
    public function getFundsByIDStockholder ($id){
        $funds = App\fund::where('idstock',$id)->where('extends',null)->get();
        $stockholder = App\Stockholder::where('id',$id)->first();
        if($funds->count()>0){
            return response()->json(['error'=>false,
                'message'=>'ok.',
                'fund'=>$funds,
                'stock'=>$stockholder]);
        }else{
            return response()->json(['error'=>true,
                'message'=>'No hay creditos.',
                'fund'=>null,
            'stock' => $stockholder]);
        }
    }

    public function updateFileByIDStockholder($idControlFund,$idFile){
        $controlFund = App\Control_Fund::where('id',$idControlFund)->get();
        //$controlFund->fileid = $idFile;
        if(!$controlFund->isEmpty()){
            try {
                $controlFund = App\Control_Fund::where('id',$idControlFund)->find($idControlFund);
                $controlFund->fileid = $idFile;
                $controlFund->save();
                return response()->json(['error'=>false,'message'=>'Disposicion actualizada correctamente']);
            } catch (Exception $e) {
                return response()->json(['error'=>false,'message'=>'Disposicion no se pudo actualizar.','errors'=>$e->getMessage()]);
            }
        }
        $controlFund->save();
    }
    public function getCtrlByIDStockholder ($idStock,$id,Request $request){
        $funds = App\fund::where('id',$id)->orWhere('extends',$id)->get();
        $ctrl = App\Control_Fund::where('credit',$id)->orderBy('period','ASC')->get();
        $stockholder = App\Stockholder::where('id',$idStock)->first();
        if($ctrl->count()>0){
            return response()->json(['error'=>false,
                'message'=>'ok.',
                'fund'=>$funds,
                'cntrl'=>$ctrl,
                'stock' => $stockholder]);
        }else{
            return response()->json(['error'=>true,
                'message'=>'No hay creditos.',
                'fund'=>$funds,
                'cntrl'=>$ctrl,
                'idstock'=>$idStock,
                'id'=>$id,
                'stock' => $stockholder]);
        }
    }

}
