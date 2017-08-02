<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Average_Money_Loaned extends Model {
    protected $table = 'average_money_loaned';
    use SoftDeletes;
    protected $dates = ['deleted_at',];

    protected $fillable = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships

}
