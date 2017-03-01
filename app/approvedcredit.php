<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class approvedcredit extends Model {
    protected $table = 'credits_approved';
    use SoftDeletes;

	protected $fillable = ['type','amount','start_date','term','interest','iva','interest_arrear','grace_days','currency',];

	protected $dates = ['deleted_at',];


	// Relationships

}
