<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 9/26/17
 * Time: 5:12 PM
 */
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use App\Http\Controllers\OccupationsController as OC;
class OccupationsTableSeeder extends Seeder
{
    public function run(){
        $OC = new OC();
        $request = new Request();
        $request->replace(['name'=>'SuperUser']);
        $OC->add($request);
    }

}