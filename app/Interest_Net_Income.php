<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interest_Net_Income extends Model {
    protected $table = 'interest_net_income';
    use SoftDeletes;
    protected $dates = ['deleted_at',];

    protected $fillable = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships

}
