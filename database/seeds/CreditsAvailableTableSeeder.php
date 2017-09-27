<?php
/**<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 9/26/17
 * Time: 5:12 PM
 */
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use App\Http\Controllers\CreditsController as CC;
class CreditsAvailableTableSeeder extends Seeder
{
    public function run(){
        $CC = new CC();

        $creditTypes = ["Pago al Final", "Revolvente"];
        $request = new Request;
        foreach($creditTypes as $creditType){
            $request->replace(['name'=>$creditType]);
            $CC->addCreditType($request);
        }
    }
}