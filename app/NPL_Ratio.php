<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NPL_Ratio extends Model {
    protected $table = 'npl_ratio';
    use SoftDeletes;
    protected $dates = ['deleted_at',];

    protected $fillable = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships

}
