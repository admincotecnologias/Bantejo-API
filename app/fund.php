<?php
namespace App;
use App\Control_Fund;
use App\fund;
use Carbon\Carbon;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class fund extends Model {

    protected $table = 'fund';
    use SoftDeletes;

	protected $fillable = [
        'idstock',
        'amount',
        'start_date',
        'term',
        'interest',
        'iva',
        'interest_arrear',
        'grace_days',
        'currency',
        'todo',
        'status',
        'extends',
    ];

	protected $dates = ['deleted_at',];

	public static $rules = [
		// Validation rules
        'create'=>[
            'idstock'=>'required|integer|exists:stockholder,id',
            'amount'=>'required|numeric',
            'start_date'=>'required|date',
            'term'=>'required|integer',
            'interest'=>'required|numeric',
            'iva'=>'required|numeric',
            'interest_arrear'=>'required|numeric',
            'grace_days'=>'required|numeric',
            'currency'=>'required|max:5',
            'todo'=>'required|max:500',
            'status'=>'required|max:20',
            'extends'=>'required|integer|nullable|exists:fund,id',
        ]
	];

	public $appends = ['lastmove','datelimit','grace','interes'];

	//Appends

    public function getLastmoveAttribute(){
        $last = DB::table('fund')
            ->join('control_funds','control_funds.credit','=',DB::raw($this->id))
            ->select('control_funds.capital_balance')
            ->where('control_funds.credit','=',DB::raw($this->id))
            ->where('fund.idstock','=',DB::raw('(select f.idstock from fund as f where f.id = '.DB::raw($this->id).' limit '.DB::raw(1).')'))
            ->orderBy('control_funds.created_at','DESC')
            ->first();
        return $last==null?0:$last->capital_balance;
    }
    public function getInteresAttribute(){
        $last = DB::table('fund')
            ->selectRaw('control_funds.*')
            ->join('control_funds','control_funds.credit','=',DB::raw($this->id))
            ->where('control_funds.credit','=',DB::raw($this->id))
            ->where('fund.idstock','=',DB::raw('(select f.idstock from fund as f where f.id = '.DB::raw($this->id).' limit '.DB::raw(1).')'))
            ->orderBy('control_funds.id','DESC')
            ->first();
        if($last==null){
            return 0;
        }else{
            $startDate = Carbon::parse($last->period);
            $newDate = Carbon::today();
            $finalDate = Carbon::parse($this->start_date)->addMonth(intval($this->term));
            $graceDate = Carbon::parse($this->start_date)->addMonth(intval($this->term))->addDays(intval($this->grace_days));
            $dateDif = $startDate->diffInDays($newDate);
            if($finalDate->timestamp < $newDate->timestamp){
                $dateDif = $startDate->diffInDays($finalDate);
            }
            $dateDifGrace = $newDate->diffInDays($graceDate);
            $interes = ($last->interest_balance)+((($last->capital_balance*($this->interest/100))/365)*$dateDif);
            $interesMoratorio = 0;
            if($newDate->timestamp > $graceDate->timestamp){
                $interesMoratorio = ($last->interest_arrear_balance)+(((($last->capital_balance+$interes)*($this->interest_arrear/100))/365)*$dateDifGrace);
            }
            return $interes + $interesMoratorio;
        }
    }
    public function getDatelimitAttribute(){
        $last = Carbon::parse($this->start_date);
        $last = $last->addMonth($this->term);
        return $last->timestamp;
    }
    public function getGraceAttribute(){
        $last = Carbon::parse($this->start_date);
        $last = $last->addMonth($this->term);
        $grace = $last->addDays($this->grace_days);
        return $grace->timestamp;
    }

	// Relationships

}
