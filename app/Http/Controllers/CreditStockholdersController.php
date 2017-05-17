<?php namespace App\Http\Controllers;
use Illuminate\Http\Request as Request;
use App;
use Illuminate\Support\Facades\DB;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class CreditStockholdersController extends Controller {

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
    public function getFundsByIDStockholder ($id,Request $request){
        $funds = App\fund::where('idstock',$id)->get();
        if($funds->count()>0){
            return response()->json(['error'=>false,
                'message'=>'ok.',
                'fund'=>$funds]);
        }else{
            return response()->json(['error'=>true,
                'message'=>'No hay creditos.',
                'fund'=>null]);
        }
    }
    public function getCtrlByIDStockholder ($id,Request $request){
        $funds = App\Control_Fund::where('idstock',$id)->get();
        if($funds->count()>0){
            return response()->json(['error'=>false,
                'message'=>'ok.',
                'fund'=>$funds]);
        }else{
            return response()->json(['error'=>true,
                'message'=>'No hay creditos.',
                'fund'=>null]);
        }
    }

}
