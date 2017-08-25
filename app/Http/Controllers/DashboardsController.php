<?php namespace
App\Http\Controllers;
use App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

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

    public function MargenFinancieroFechas(Request $request){
        $startDate = Date($request->input("startDate"));
        $endDate = Date($request->input("endDate"));
        $samples= App\Financial_Margin::whereBetween(DB::raw('created_at'), [$startDate, $endDate])->get();
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
        return response()->json(['error'=>false,'samples'=>$filtered_samples,'latestSample'=>App\NPL_Ratio::max('idsample')]);
    }
    public function MorosidadTotalFechas(Request $request){
        $startDate = Date($request->input("startDate"));
        $endDate = Date($request->input("endDate"));
        //Obtener los datos de las muestras
        $samples= App\NPL_Ratio::whereBetween(DB::raw('created_at'), [$startDate, $endDate])->get();
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
    public function InteresNeto(){
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
    public function InteresNetoFechas(Request $request){
        $startDate = Date($request->input("startDate"));
        $endDate = Date($request->input("endDate"));
        //Obtener los datos de las muestras
        $samples= App\Interest_Net_Income::whereBetween(DB::raw('created_at'), [$startDate, $endDate])->get();
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
        return response()->json(['error'=>false,'samples'=>$filtered_samples,'latestSample'=>App\Average_Money_Loaned::max('idsample')]);
    }
    public function CarteraPromedioFechas(Request $request){
        $startDate = Date($request->input("startDate"));
        $endDate = Date($request->input("endDate"));
        //Obtener los datos de las muestras
        $samples= App\Average_Money_Loaned::whereBetween(DB::raw('average_money_loaned.created_at'), [$startDate, $endDate])
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
        $samples= App\Average_Money_Borrowed::where(DB::raw('YEAR(average_money_borrowed.created_at)'), '=', $currentYear)
            ->leftJoin('stockholder','stockholder.id','=','average_money_borrowed.idstockholder')->
            select('average_money_borrowed.*','stockholder.name')->get();
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
        return response()->json(['error'=>false,'samples'=>$filtered_samples,
                                'latestSample'=>App\Average_Money_Borrowed::max('idsample')]);
    }
    public function DeudaPromedioFechas(Request $request){
        $startDate = Date($request->input("startDate"));
        $endDate = Date($request->input("endDate"));
        //Obtener los datos de las muestras
        $samples= App\Average_Money_Borrowed::whereBetween(DB::raw('average_money_borrowed.created_at'), [$startDate, $endDate])
            ->leftJoin('stockholder','stockholder.id','=','average_money_borrowed.idstockholder')->
            select('average_money_borrowed.*','stockholder.name')->get();
        if($samples->isEmpty()){
            return response()->json(['error'=>true,'message'=>'No hay muestras de deuda promedio.']);
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
