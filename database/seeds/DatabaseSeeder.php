<?php

use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use App\Http\Controllers\OccupationsController as OC;
use App\Http\Controllers\UserController as UC;
use App\Http\Controllers\PagesController as PaC;
use App\Http\Controllers\PermissionsController as PeC;
use App\Http\Controllers\CreditsController as CC;
use App\Http\Controllers\EmployeesController as EC;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->call('PagesTableSeeder');
        //$this->call('OccupationsTableSeeder');
        //$this->call('CreditsAvailableTableSeeder');
        //$this->call('UsersTableSeeder');

        $PaC = new PaC();
        $request = new Request();
        $pages = ['Creditos','Solicitudes','Clientes','Fondeadores','Usuarios','Dashboard'];
        $pageIds = [];
        foreach($pages as $page){
            $request->replace(['url'=>$page]);
            $response = $PaC->add($request);
            $JSONResponse = $response->getData(true);
            $pageIds[]=$JSONResponse['id'];
        }
        $OC = new OC();
        $request->replace(['name'=>'SuperUser']);
        $JSONResponse = $OC->add($request)->getData(true);
        $occupationId = $JSONResponse['id'];
        $CC = new CC();

        $creditTypes = ["Pago al Final", "Revolvente"];
        foreach($creditTypes as $creditType){
            $request->replace(['name'=>$creditType]);
            $CC->addCreditType($request);
        }
        $UC = new UC();
        $request->replace(['email'=>'fesquer@opessa.net','name'=>'Fransisco','password'=>'prueba123','password_confirmation'=>'prueba123']);
        $response = $UC->add($request);
        $JSONResponse = $response->getData(true);
        $userId = $JSONResponse['id'];

        $PeC = new PeC();
        foreach($pageIds as $pageId){
            $request->replace(['show'=>true,'insert'=>true,'edit'=>true,'delete'=>true,'report'=>true,
                'iduser'=>$userId,'idpage'=>$pageId]);
            $PeC->add($request);
        }
        $EC = new EC();
        $request->replace(['name'=>'Fransico','lastname'=>'Esquer','iduser'=>$userId,'idoccupation'=>$occupationId]);
        $EC->add($request);

    }
}
