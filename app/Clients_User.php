<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clients_User extends Model {
    use SoftDeletes;

    protected $table = "clients_user";
    protected $fillable = ['name', 'email', 'password','api_token','last_ip','last_connection','idclient'];

    protected $hidden = [
        'password',
    ];

    protected $dates = ['deleted_at',];

    // Relationships

}
