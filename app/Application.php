<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model {
	use SoftDeletes;

	protected $fillable = ['amountrequest','amountsuccess','applicationdate','term','rate','arrears','periodicity','graceperiod','tax','interesttax','interest','status',];

	protected $dates = ['deleted_at',];


}
