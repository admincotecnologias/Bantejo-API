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
        //Obtener los datos de las muestras
        $samples= App\NPL_Ratio::where(DB::raw('YEAR(created_at)'), '=', $currentYear)->get();
        if($samples->isEmpty()){
            return response()->json(['error'=>true,'message'=>'No hay muestras de morosidad total.']);
        }
        $filtered_samples = collect();
        //Iteramos todas las muestras, y las filtramos por su ID de muestra
        foreach($samples as $sample){
            $sampleid = $sample->idsample;
            if(!isset($filtered_samples[$sampleid])){
                $filtered_samples[$sampleid] = collect();
            }
            $filtered_samples[$sampleid]->push($sample);
        }
        return response()->json(['error'=>false,'samples'=>$filtered_samples]);
    }
    public function InteresesNeto(){
        $currentYear = Date('Y');
        //Obtener los datos de las muestras
        $samples= App\Interest_Net_Income::where(DB::raw('YEAR(created_at)'), '=', $currentYear)->get();
        if($samples->isEmpty()){
            return response()->json(['error'=>true,'message'=>'No hay muestras de morosidad total.']);
        }
        $filtered_samples = collect();
        //Iteramos todas las muestras, y las filtramos por su ID de muestra
        foreach($samples as $sample){
            $sampleid = $sample->idsample;
            if(!isset($filtered_samples[$sampleid])){
                $filtered_samples[$sampleid] = collect();
            }
            $filtered_samples[$sampleid]->push($sample);
        }
        return response()->json(['error'=>false,'samples'=>$filtered_samples]);
    }
    public function CarteraPromedio(){
        $currentYear = Date('Y');
        //Obtener los datos de las muestras
        $samples= App\Average_Money_Loaned::where(DB::raw('YEAR(average_money_loaned.created_at)'), '=', $currentYear)
            ->leftJoin('users','users.id','=','average_money_loaned.idclient')->
            select('average_money_loaned.*','users.name')->get();
        if($samples->isEmpty()){
            return response()->json(['error'=>true,'message'=>'No hay muestras de morosidad total.']);
        }
        $filtered_samples = collect();
        //Iteramos todas las muestras, y las filtramos por su ID de muestra
        foreach($samples as $sample){
            $sampleid = $sample->idsample;
            if(!isset($filtered_samples[$sampleid])){
                $filtered_samples[$sampleid] = collect();
            }
            $filtered_samples[$sampleid]->push($sample);
        }
        return response()->json(['error'=>false,'samples'=>$filtered_samples]);
    }
    public function DeudaPromedio(){
        $currentYear = Date('Y');
        //Obtener los datos de las muestras
        $samples= App\Average_Money_Borrowed::where(DB::raw('YEAR(created_at)'), '=', $currentYear)->get();
        if($samples->isEmpty()){
            return response()->json(['error'=>true,'message'=>'No hay muestras de morosidad total.']);
        }
        $filtered_samples = collect();
        //Iteramos todas las muestras, y las filtramos por su ID de muestra
        foreach($samples as $sample){
            $sampleid = $sample->idsample;
            if(!isset($filtered_samples[$sampleid])){
                $filtered_samples[$sampleid] = collect();
            }
            $filtered_samples[$sampleid]->push($sample);
        }
        return response()->json(['error'=>false,'samples'=>$filtered_samples]);
    }


}
