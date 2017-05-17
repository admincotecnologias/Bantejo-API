<?php
namespace App;
use App\Control_Fund;
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

	protected $appends = ['capital'];

	//Appends

    public function getLastMoveAttribute(){
        $last = DB::table('fund')
            ->join('control_funds','control_funds.credit','=','fund.id')
            ->select(DB::raw('control_funds.capital_balance as capital,fund.id'))
            ->groupBy('fund.id')
            ->orderBy('control_funds.period','DESC')
            ->where('control_funds.extends','!=',null)
            ->get();
        return $last->capital;
    }

	// Relationships

}
