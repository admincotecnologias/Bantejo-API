<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 9/26/17
 * Time: 5:13 PM
 */
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController as UC;
use App\Http\Controllers\UserController as PC;
use App\Http\Controllers\UserController as OC;
class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $UC = new UC();
        $request = new Request();
        $request->replace(['email'=>'enrique@dummy.com','name'=>'Fransisco','password'=>'prueba123','password_confirmation'=>'prueba123']);
        $response = $UC->add($request);
        $JSONResponse = $response->getData(true);
        $userId = $JSONResponse['id'];
    }
}