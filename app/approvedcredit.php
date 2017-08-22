<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon as Carbon;

class approvedcredit extends Model {
    protected $table = 'credits_approved';
    use SoftDeletes;

	protected $fillable = ['application','type','amount','start_date','term','interest','iva','interest_arrear','grace_days','currency','todo','status','extends',];

	protected $dates = ['deleted_at',];

	public static $rules = [
        'create'=>[
            'application'=>'required|integer',
            'type'=>'required|integer',
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
            'extends'=>'required|integer|nullable',
        ]
    ];
	// Relationships
    public function app()
    {
        return $this->hasOne('App\Applications');
    }
    protected $appends = ['lastmove','vencimiento','intereses'];
    public function getLastmoveAttribute(){
        try{
            $move = controlcredit::where('credit',$this->id)->orderBy('id','DESC')->firstOrFail();
        }catch (\Exception $ex){
            if($this->type==1){
                return $this->amount;
            }if($this->type==2){
                return 0;
            }
        }
        return $move->capital_balance;
    }
    public function getInteresesAttribute(){
        try{
            $lastMove = controlcredit::where('credit',$this->id)->orderBy('period','DESC')->firstOrFail();
            $interest_balance = $lastMove->interest_balance;
            $interest_arrear_balance = $lastMove->interest_arrear_balance;
            $startDate = Carbon::parse($lastMove->period);
            $newDate = Carbon::today();
            if($newDate->timestamp > $startDate->timestamp){
                $finalDate = Carbon::parse($this->start_date)->addMonth(intval($this->term));
                $graceDate = Carbon::parse($this->start_date)->addMonth(intval($this->term))->addDays(intval($this->grace_days));
                $dateDif = $startDate->diffInDays($newDate);
                if($finalDate->timestamp < $newDate->timestamp){
                    $dateDif = $startDate->diffInDays($finalDate);
                }
                $dateDifGrace = $newDate->diffInDays($graceDate);
                $capital_balance = floatval($lastMove->capital_balance);
                $interest_balance = floatval($lastMove->interest_balance) + (((floatval($this->interest) / 100 / 365) * floatval($capital_balance)) * $dateDif);
                if ($newDate->timestamp > $graceDate->timestamp) {
                    $interest_arrear_balance = $lastMove->interest_arrear_balance + ((($this->interest_arrear / 100 / 365) * $dateDifGrace) * ($capital_balance + $interest_balance));
                } else {
                    $interest_arrear_balance = 0;
                }
            }
            return $interest_balance+$interest_arrear_balance;
        }catch (\Exception $ex){
            if($this->type==1){
                return $this->amount;
            }if($this->type==2){
                return 0;
            }
        }
        return 0;
    }
    public function getVencimientoAttribute(){
        $dateVence = Carbon::parse($this->start_date)->addMonth($this->term);
        $dateGracia = Carbon::parse($this->start_date)->addMonth($this->term)->addDays($this->grace_days);
        $dateNow = Carbon::now();
        if($dateNow>$dateVence){
            if($dateNow>$dateGracia){
                return 3;
            }
            return 2;
        }
        return 1;
    }
}
