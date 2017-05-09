<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\ProviderShareholder;
use Validator;


class StockholdersController extends Controller {

	public function createStockholder(Request $request){
	    $validate = Validator::make($request->all(),App\Stockholder::$rules['create']);
        if ($validate->fails()) {
            return response()->json([
                'error'=>true,
                'message'=>'error al validar campos.',
                'errors'=>$validate->errors()->all()
            ]);
        }
        else{
            $stockholder = App\Stockholder::create($request->all());
            $stockholder->save();
            if($stockholder->id > 0){
                return response()->json([
                    'error'=>false,
                    'message'=>'Guardado.',
                    'stockholder'=>$stockholder->id
                ]);
            }else{
                return response()->json([
                    'error'=>true,
                    'message'=>'Error al guardar.'
                ]);
            }
        }
	}

	public function allStockholder(Request $request){
	    $stockholder = App\Stockholder::all();
        return response()->json([
            'error'=>false,
            'message'=>'ok',
            'stockholders'=>$stockholder
        ]);
    }

    public  function showStockholder($id){
	    $stockholder = App\Stockholder::where('id',$id)->first();
	    if($stockholder->id){
	        $managers = App\ProviderShareholder::where('idstockholder',$stockholder->id)->get();
	        $accounts = App\StockAccount::where('idstockholder',$stockholder->id)->get();
        }
        return response()->json([
            'error'=>false,
            'message'=>'ok',
            'stockholders'=>$stockholder,
            'managers'=>$managers,
            'accounts'=>$accounts,
        ]);
    }

    public  function updateStockholder($id,Request $request){
        $validate = Validator::make($request->all(),App\Stockholder::$rules['update']);
        if ($validate->fails()) {
            return response()->json([
                'error'=>true,
                'message'=>'error al validar campos.',
                'errors'=>$validate->errors()->all()
            ]);
        }
        else{
            $stockholder = App\Stockholder::where('id',$id)->update($request->all());
            $stockholder->save();
            if($stockholder->id > 0){
                return response()->json([
                    'error'=>false,
                    'message'=>'Guardado.',
                    'stockholder'=>$stockholder->id
                ]);
            }else{
                return response()->json([
                    'error'=>true,
                    'message'=>'Error al guardar.'
                ]);
            }
        }
    }

    public  function deleteStockholder($id){
        $stockholder = App\Stockholder::where('id',$id)->delete();
        return response()->json([
            'error'=>false,
            'message'=>'ok',
            'stockholders'=>$stockholder
        ]);
    }

    public function createManager(Request $request){
        $validate = Validator::make($request->all(),App\ProviderShareholder::$rules['create']);
        if ($validate->fails()) {
            return response()->json([
                'error'=>true,
                'message'=>'error al validar campos.',
                'errors'=>$validate->errors()->all()
            ]);
        }
        else{
            $stockholder = App\ProviderShareholder::create($request->all());
            $stockholder->save();
            if($stockholder->id > 0){
                return response()->json([
                    'error'=>false,
                    'message'=>'Guardado.',
                    'stockholder'=>$stockholder->id
                ]);
            }else{
                return response()->json([
                    'error'=>true,
                    'message'=>'Error al guardar.'
                ]);
            }
        }
    }

    public function allManager(Request $request){
        $stockholder = App\ProviderShareholder::all();
        return response()->json([
            'error'=>false,
            'message'=>'ok',
            'stockholders'=>$stockholder
        ]);
    }

    public  function showManager($id){
        $stockholder = App\ProviderShareholder::where('id',$id)->first();
        return response()->json([
            'error'=>false,
            'message'=>'ok',
            'stockholders'=>$stockholder
        ]);
    }

    public  function updateManager($id,Request $request){
        $validate = Validator::make($request->all(),App\ProviderShareholder::$rules['update']);
        if ($validate->fails()) {
            return response()->json([
                'error'=>true,
                'message'=>'error al validar campos.',
                'errors'=>$validate->errors()->all()
            ]);
        }
        else{
            $stockholder = App\ProviderShareholder::where('id',$id)->update($request->all());
            $stockholder->save();
            if($stockholder->id > 0){
                return response()->json([
                    'error'=>false,
                    'message'=>'Guardado.',
                    'stockholder'=>$stockholder->id
                ]);
            }else{
                return response()->json([
                    'error'=>true,
                    'message'=>'Error al guardar.'
                ]);
            }
        }
    }

    public  function deleteManager($id){
        $stockholder = App\ProviderShareholder::where('id',$id)->delete();
        return response()->json([
            'error'=>false,
            'message'=>'ok',
            'stockholders'=>$stockholder
        ]);
    }

    public function createAccount(Request $request){
        $validate = Validator::make($request->all(),App\StockAccount::$rules['create']);
        if ($validate->fails()) {
            return response()->json([
                'error'=>true,
                'message'=>'error al validar campos.',
                'errors'=>$validate->errors()->all()
            ]);
        }
        else{
            $stockholder = App\StockAccount::create($request->all());
            $stockholder->save();
            if($stockholder->id > 0){
                return response()->json([
                    'error'=>false,
                    'message'=>'Guardado.',
                    'stockholder'=>$stockholder->id
                ]);
            }else{
                return response()->json([
                    'error'=>true,
                    'message'=>'Error al guardar.'
                ]);
            }
        }
    }

    public function allAccount(Request $request){
        $stockholder = App\ProviderShareholder::all();
        return response()->json([
            'error'=>false,
            'message'=>'ok',
            'stockholders'=>$stockholder
        ]);
    }

    public  function showAccount($id){
        $stockholder = App\StockAccount::where('id',$id)->first();
        return response()->json([
            'error'=>false,
            'message'=>'ok',
            'stockholders'=>$stockholder
        ]);
    }

    public  function updateAccount($id,Request $request){
        $validate = Validator::make($request->all(),App\StockAccount::$rules['update']);
        if ($validate->fails()) {
            return response()->json([
                'error'=>true,
                'message'=>'error al validar campos.',
                'errors'=>$validate->errors()->all()
            ]);
        }
        else{
            $stockholder = App\StockAccount::where('id',$id)->update($request->all());
            $stockholder->save();
            if($stockholder->id > 0){
                return response()->json([
                    'error'=>false,
                    'message'=>'Guardado.',
                    'stockholder'=>$stockholder->id
                ]);
            }else{
                return response()->json([
                    'error'=>true,
                    'message'=>'Error al guardar.'
                ]);
            }
        }
    }

    public  function deleteAccount($id){
        $stockholder = App\StockAccount::where('id',$id)->delete();
        return response()->json([
            'error'=>false,
            'message'=>'ok',
            'stockholders'=>$stockholder
        ]);
    }

}
