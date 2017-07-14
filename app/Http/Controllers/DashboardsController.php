<?php namespace
App\Http\Controllers;
use App;


class DashboardsController extends Controller {

    public function MorosidadTotal(){
        $data= App\approvedcredit::where('credits_approved.extends',null)
            ->get()
            ->groupBy('vencimiento')->toArray();
        $total = 0;
        $totalCapital = 1;
        if(isset($data[1])){
            $capital = 0;
            foreach ($data[1] as $item){
                $capital += $item['lastmove'];
            }
            $totalCapital += $capital;
            $data[1] = [
                'total'=>count($data[1]),
                'porcentaje'=>0,
                'cartera'=>$capital,
                'carteraporcentaje'=>0
            ];
            $total += $data[1]['total'];
        }
        if(isset($data[2])){
            $capital = 0;
            foreach ($data[2] as $item){
                $capital += $item['lastmove'];
            }
            $totalCapital += $capital;
            $data[2] = [
                'total'=>count($data[2]),
                'porcentaje'=>0,
                'cartera'=>$capital,
                'carteraporcentaje'=>0
            ];
            $total += $data[2]['total'];
        }
        if(isset($data[3])){
            $capital = 0;
            foreach ($data[3] as $item){
                $capital += $item['lastmove'];
            }
            $totalCapital += $capital;
            $data[3] = [
                'total'=>count($data[3]),
                'porcentaje'=>0,
                'cartera'=>$capital,
                'carteraporcentaje'=>0
            ];
            $total += $data[3]['total'];
        }
        if(isset($data[1])){
            $data[1]['porcentaje'] = ($data[1]['total']*100)/$total;
            $data[1]['carteraporcentaje'] = ($data[1]['cartera']*100)/$totalCapital;
        }
        if(isset($data[2])){
            $data[2]['porcentaje'] = ($data[2]['total']*100)/$total;
            $data[2]['carteraporcentaje'] = ($data[2]['cartera']*100)/$totalCapital;
        }
        if(isset($data[3])){
            $data[3]['porcentaje'] = ($data[3]['total']*100)/$total;
            $data[3]['carteraporcentaje'] = ($data[3]['cartera']*100)/$totalCapital;
        }
        $data["total"] = $total;
        $data["totalcapital"] = $totalCapital;
        return response()->json($data);
    }
    public function InteresesNeto(){

        $credits = App\approvedcredit::where('extends',null)->get();
        $total = 0;
        foreach ($credits as $item){
            $total += $item->intereses;
        }
        return response()->json(['intereses'=>$total]);
    }

}
