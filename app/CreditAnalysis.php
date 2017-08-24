<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditAnalysis extends Model {
    use SoftDeletes;
    protected $table = 'creditanalysis';
    protected $fillable = ['observation','applicationid'];

    protected $dates = ['deleted_at',];

    public static $rules = [
        // Validation rules
        'create'=>[
            'observation' => 'required|max:255',
            'applicationid' => 'required|exists:applications,id|nullable'
        ]
    ];

    // Relationships

}
