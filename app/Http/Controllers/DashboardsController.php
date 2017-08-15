<?php namespace
App\Http\Controllers;
use App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardsController extends Controller {

    public function MargenFinanciero(){
        $currentYear = Date('Y');
        $samples= App\Financial_Margin::where(DB::raw('YEAR(created_at)'), '=', $currentYear)->get();
        if(!$samples->isEmpty()){
            return response()->json(['error'=>false,'samples'=>$samples]);
        }else{
            return response()->json(['error'=>true,'message'=>'No hay muestras de margen financiero.']);
        }
    }
    public function MorosidadTotal(){
        $currentYear = Date('Y');
        //Obtener los datos agrupados por numero de muestra
        $samples= App\NPL_Ratio::where(DB::raw('YEAR(created_at)'), '=', "2017");
        $samples = $samples->groupBy('idsample')->get();
        if(!$samples->isEmpty()){
            return response()->json(['error'=>false,'samples'=>$samples]);
        }else{
            return response()->json(['error'=>true,'message'=>'No hay muestras de morosidad total.']);
        }

    }
    public function InteresesNeto(){
        $currentYear = Date('Y');
        $samples= App\Interest_Net_Income::where(DB::raw('YEAR(created_at)'), '=', $currentYear)->groupBy('idsample')->get();
        if(!$samples->isEmpty()){
            return response()->json(['error'=>false,'samples'=>$samples]);
        }else{
            return response()->json(['error'=>true,'message'=>'No hay muestras de intereses neto.']);
        }
    }
    public function CarteraPromedio(){
        $currentYear = Date('Y');
        $samples = App\Average_Money_Loaned::where(DB::raw('YEAR(created_at)'), '=', $currentYear)->groupBy('idsample')->get();
        Log::warning($samples);
        if(!$samples->isEmpty()){
            return response()->json(['error'=>false,'samples'=>$samples]);
        }else{
            return response()->json(['error'=>true,'message'=>'No hay muestras de cartera promedio.']);
        }
    }

    public function DeudaPromedio(){
        $currentYear = Date('Y');
        $samples = App\Average_Money_Borrowed::where(DB::raw('YEAR(created_at)'), '=', $currentYear)->groupBy('idsample')->get();
        if(!$samples->isEmpty()){
            return response()->json(['error'=>false,'samples'=>$samples]);
        }else{
            return response()->json(['error'=>true,'message'=>'No hay muestras de deuda promedio.']);
        }
    }

}
