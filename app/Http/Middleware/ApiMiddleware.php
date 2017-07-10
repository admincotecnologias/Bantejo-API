<?php

namespace App\Http\Middleware;
use App\Http\Controllers;
use App\Http\Controllers\Api_authsController as Api_authsController;

use Closure;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     protected $auth;
     public function __construct()
    {
        //
        $this->auth = new Api_authsController; 
    }
               
    public function handle($request, Closure $next)
    {
        $error = $this->auth->checkRole($request);
        if($error->getData()->error){
            return response()->json($error->getData(),401);
        }
        return $next($request);
    }
}
