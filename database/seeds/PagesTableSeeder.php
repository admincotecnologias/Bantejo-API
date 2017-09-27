<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 9/26/17
 * Time: 5:12 PM
 */
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use App\Http\Controllers\PagesController as PC;
class PagesTableSeeder extends Seeder
{

    public function run(){
        $PC = new PC();
        $request = new Request();
        $pages = ['Creditos','Solicitudes','Clientes','Fondeadores','Usuarios','Dashboard'];
        foreach($pages as $page){
            $request->replace(['url'=>$page]);
            $PC->add($request);
        }


    }
}