<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Financial_Margin extends Model {
    protected $table = 'financial_margin';
    use SoftDeletes;
    protected $dates = ['deleted_at',];

    protected $fillable = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
}
