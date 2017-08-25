<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnalysisFiles extends Model {
    use SoftDeletes;
    protected $table = 'analysisfiles';
    protected $fillable = ['analysisid','fileid'];

    protected $dates = ['deleted_at',];

    public static $rules = [
        'create'=>[
            // Validation rules
            'fileid' => 'required|exists:files,id|nullable',
            'applicationid' => 'required|exists:applications,id|nullable'
        ]
    ];

    // Relationships

}
