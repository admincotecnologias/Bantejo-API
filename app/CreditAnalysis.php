<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditAnalysis extends Model {
    use SoftDeletes;
    protected $table = 'creditanalysis';
    protected $fillable = ['observation','applicationid','start_date'];

    protected $dates = ['deleted_at',];

    public static $rules = [
        // Validation rules
        'create'=>[
            'observation' => 'required|max:255',
            'applicationid' => 'required|exists:applications,id|nullable',
            'start_date' => 'required|date'
        ],
        'update'=>[
            'observation' => 'required|max:255|string',
            'start_date' => 'required|date',
        ]
    ];

    // Relationships

}
