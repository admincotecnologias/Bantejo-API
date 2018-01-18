<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EqualMonthlyPay extends Model {
    protected $table = 'equal_monthly_pay';
    use SoftDeletes;

    protected $fillable = ['creditid','monthly_pay'];

    protected $dates = ['deleted_at'];

    public static $rules = [
        'create'=>[
            'creditid' => 'required|integer|exists:credits_approved,id',
            'monthly_pay' => 'required|double',
        ],
        'update'=>[
            'creditid' => 'required|integer|exists:credits_approved,id',
            'monthly_pay' => 'required|double',
        ]
    ];

}