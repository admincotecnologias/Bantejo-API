<?php
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|

 /**
 * Routes for resource api_auth
 */

use Carbon\Carbon;
$app->get('Time',function(){
    return Carbon::now();
});
$app->post('Deletedis','UserController@add');
$app->group(['prefix' => 'AdminAuth'], function() use ($app) {
    $app->post('LogIn', 'Api_authsController@AdminLogIn');
    $app->get('LogOut','Api_authsController@AdminLogOut');
});

$app->group(['prefix' => 'ClientsAuth'], function() use ($app) {
    $app->post('LogIn', 'Api_authsController@ClientsLogIn');
    $app->get('LogOut','Api_authsController@ClientsLogOut');
});


$app->post('Register','UserController@addClient');

$app->group(['prefix' => 'Clients','middleware'=>'ClientsApi'], function() use ($app) {

    /**
     * Routes for resource applications
     */

    $app->group(['prefix' => 'Solicitudes'], function() use ($app) {
        $app->get('all', 'ApplicationsController@all');
        $app->get('all/Clients', 'ApplicationsController@ClientsToCredit');
        $app->post('add','ApplicationsController@add');
        $app->get('show/{id}', 'ApplicationsController@show');
        $app->put('update/{id}', 'ApplicationsController@update');
        $app->delete('delete/{id}', 'ApplicationsController@delete');
        $app->get('report/{id}', 'ApplicationsController@report');

        $app->get('all/AvalCredito', 'CreditaidsController@all');
        $app->post('add/AvalCredito','CreditaidsController@add');
        //$app->get('show/{id}/AvalCredito', 'CreditaidsController@show');
        $app->put('update/{id}/AvalCredito', 'CreditaidsController@update');
        $app->delete('delete/{id}/AvalCredito', 'CreditaidsController@delete');
        $app->get('report/{id}/AvalCredito', 'CreditaidsController@report');

        //$app->get('all/FilesApplication', 'FilesController@all');
        $app->post('add/FilesApplication','FilesController@add');
        $app->get('show/{id}/FilesApplication', 'FilesController@ReturnFile');
/*
        $app->get('/all/Credits', 'CreditsController@allCreditApproved');
        $app->get('show/{id}/Credits', 'CreditsController@showCreditApproved');
        $app->post('add/Credits', 'CreditsController@addCreditApproved');
        $app->post('add/pay/Credits','CreditsController@addCreditPay');
        $app->put('credits/{id}/Credits', 'CreditsController@put');
        $app->delete('credits/{id}/Credits', 'CreditsController@remove');
*/
    });

    $app->group(['prefix' => 'Creditos'], function() use ($app) {
        $app->get('/all', 'CreditsController@allCreditApproved');
        $app->get('show/{id}', 'CreditsController@showCreditApproved');
/*
        $app->get('all/Solicitudes', 'ApplicationsController@all');
        $app->get('all/Clients/Solicitudes', 'ApplicationsController@ClientsToCredit');
        $app->post('add/Solicitudes','ApplicationsController@add');
        $app->get('show/{id}/Solicitudes', 'ApplicationsController@show');
        $app->put('update/{id}/Solicitudes', 'ApplicationsController@update');
        $app->delete('delete/{id}/Solicitudes', 'ApplicationsController@delete');
        $app->get('report/{id}/Solicitudes', 'ApplicationsController@report');
*/

        $app->post('add/FilesApplication', 'FilesController@add');
        $app->get('show/{id}/FilesApplication', 'FilesController@ReturnFile');


    });

    $app->group(['prefix' => 'Clientes'], function() use ($app) {
        $app->get('all', 'ClientsController@all');
        $app->post('add','ClientsController@add');
        $app->get('show/{id}', 'ClientsController@show');
        $app->put('update/{id}', 'ClientsController@update');
        $app->delete('delete/{id}', 'ClientsController@delete');
        $app->delete('delete/{id}/files', 'FilesClientsController@DeleteFiles');
        $app->delete('delete/{id}/manager', 'ManagerclientsController@delete');
        $app->get('report/{id}', 'ClientsController@report');

        $app->get('all/Bancos', 'BanksController@all');
        $app->post('add/Bancos','BanksController@add');
        $app->get('show/{id}/Bancos', 'BanksController@show');
        $app->put('update/{id}/Bancos', 'BanksController@update');
        $app->delete('delete/{id}/Bancos', 'BanksController@delete');
        $app->get('report/{id}/Bancos', 'BanksController@report');

        $app->get('all/AccionistasClientes', 'ClientShareholdersController@all');
        $app->post('add/AccionistasClientes','ClientShareholdersController@add');
        $app->get('show/{id}/AccionistasClientes', 'ClientShareholdersController@show');
        $app->put('update/{id}/AccionistasClientes', 'ClientShareholdersController@update');
        $app->delete('delete/{id}/AccionistasClientes', 'ClientShareholdersController@delete');
        $app->get('report/{id}/AccionistasClientes', 'ClientShareholdersController@report');

        $app->get('all/BancosClientes', 'ClientBanksController@all');
        $app->post('add/BancosClientes','ClientBanksController@add');
        $app->get('show/{id}/BancosClientes', 'ClientBanksController@show');
        $app->put('update/{id}/BancosClientes', 'ClientBanksController@update');
        $app->delete('delete/{id}/BancosClientes', 'ClientBanksController@delete');
        $app->get('report/{id}/BancosClientes', 'ClientBanksController@report');

        $app->post('add/FilesClient','FilesClientsController@add');
        $app->get('show/{id}/FilesClient', 'FilesClientsController@ReturnFile');
        $app->delete('delete/{id}/FilesClient', 'FilesClientsController@DeleteFile');

        $app->get('all/Managers', 'ManagerclientsController@all');
        $app->post('add/Managers','ManagerclientsController@add');
        $app->get('show/{id}/Managers', 'ManagerclientsController@show');
        $app->delete('delete/{id}/Managers', 'ManagerclientsController@delete');
        $app->get('show/{id}/Wallet','ClientsController@getWallet');
        $app->get('all/Applications','ApplicationsController@all');
        $app->get('all/Credits','CreditsController@allCreditApproved');


    });
});

$app->group(['prefix' => 'Admin','middleware'=>'AdminApi'], function() use($app){
    $app->get('/fund/{id}', 'CreditStockholdersController@getFundsByIDStockholder');
    $app->get('/stock', 'DashboardsController@MorosidadTotal');
    $app->group(['prefix' => 'Auth'], function() use ($app) {
        $app->post('LogIn', 'Api_authsController@LogIn');
        $app->get('LogOut','Api_authsController@LogOut');
    });
    $app->group(['prefix' => 'Roles'], function() use ($app) {
        $app->get('/', 'Api_authsController@checkAuth');
    });
    /**
     * Routes for resource User
     */
    $app->group(['prefix' => 'Usuarios'], function() use ($app) {
        $app->get('all', 'UserController@all');
        $app->post('add','UserController@add');
        $app->get('show/{id}', 'UserController@show');
        $app->put('update/{id}', 'UserController@update');
        $app->delete('delete/{id}', 'UserController@delete');
        $app->get('report/{id}', 'UserController@report');
        $app->put('update/{clientId}/Cliente', 'UserController@confirmClient');

        $app->get('all/Permisos', 'PermissionsController@all');
        $app->post('add/Permisos','PermissionsController@add');
        $app->get('show/{id}/Permisos', 'PermissionsController@show');
        $app->put('update/{id}/Permisos', 'PermissionsController@update');
        $app->delete('delete/{id}/Permisos', 'PermissionsController@delete');
        $app->get('report/{id}/Permisos', 'PermissionsController@report');

        $app->get('all/Paginas', 'PagesController@all');
        $app->post('add/Paginas','PagesController@add');
        $app->get('show/{id}/Paginas', 'PagesController@show');
        $app->put('update/{id}/Paginas', 'PagesController@update');
        $app->delete('delete/{id}/Paginas', 'PagesController@delete');
        $app->get('report/{id}/Paginas', 'PagesController@report');

        $app->get('all/Empleados', 'EmployeesController@all');
        $app->post('add/Empleados','EmployeesController@add');
        $app->get('show/{id}/Empleados', 'EmployeesController@show');
        $app->put('update/{id}/Empleados', 'EmployeesController@update');
        $app->delete('delete/{id}/Empleados', 'EmployeesController@delete');
        $app->get('report/{id}/Empleados', 'EmployeesController@report');

        $app->get('all/Puestos', 'OccupationsController@all');
        $app->post('add/Puestos','OccupationsController@add');
        //$app->get('show/{id}/Puestos', 'OccupationsController@show');
        //$app->put('update/{id}/Puestos', 'OccupationsController@update');
        $app->delete('delete/{id}/Puestos', 'OccupationsController@delete');
        //$app->get('report/{id}/Puestos', 'OccupationsController@report');
    });

    /*
     * Routes for resource client
     */
    $app->group(['prefix' => 'Clientes'], function() use ($app) {
        $app->get('all', 'ClientsController@all');
        $app->post('add','ClientsController@add');
        $app->get('show/{id}', 'ClientsController@show');
        $app->put('update/{id}', 'ClientsController@update');
        $app->delete('delete/{id}', 'ClientsController@delete');
        $app->delete('delete/{id}/files', 'FilesClientsController@DeleteFiles');
        $app->delete('delete/{id}/manager', 'ManagerclientsController@delete');
        $app->get('report/{id}', 'ClientsController@report');

        $app->get('all/Bancos', 'BanksController@all');
        $app->post('add/Bancos','BanksController@add');
        $app->get('show/{id}/Bancos', 'BanksController@show');
        $app->put('update/{id}/Bancos', 'BanksController@update');
        $app->delete('delete/{id}/Bancos', 'BanksController@delete');
        $app->get('report/{id}/Bancos', 'BanksController@report');

        $app->get('all/AccionistasClientes', 'ClientShareholdersController@all');
        $app->post('add/AccionistasClientes','ClientShareholdersController@add');
        $app->get('show/{id}/AccionistasClientes', 'ClientShareholdersController@show');
        $app->put('update/{id}/AccionistasClientes', 'ClientShareholdersController@update');
        $app->delete('delete/{id}/AccionistasClientes', 'ClientShareholdersController@delete');
        $app->get('report/{id}/AccionistasClientes', 'ClientShareholdersController@report');

        $app->get('all/BancosClientes', 'ClientBanksController@all');
        $app->post('add/BancosClientes','ClientBanksController@add');
        $app->get('show/{id}/BancosClientes', 'ClientBanksController@show');
        $app->put('update/{id}/BancosClientes', 'ClientBanksController@update');
        $app->delete('delete/{id}/BancosClientes', 'ClientBanksController@delete');
        $app->get('report/{id}/BancosClientes', 'ClientBanksController@report');

        $app->post('add/FilesClient','FilesClientsController@add');
        $app->get('show/{id}/FilesClient', 'FilesClientsController@ReturnFile');
        $app->delete('delete/{id}/FilesClient', 'FilesClientsController@DeleteFile');

        $app->get('all/Managers', 'ManagerclientsController@all');
        $app->post('add/Managers','ManagerclientsController@add');
        $app->get('show/{id}/Managers', 'ManagerclientsController@show');
        $app->delete('delete/{id}/Managers', 'ManagerclientsController@delete');
        $app->get('show/{id}/Wallet','ClientsController@getWallet');
        $app->get('all/Applications','ApplicationsController@all');
        $app->get('all/Credits','CreditsController@allCreditApproved');


    });



    /**
     * Routes for resource applications
     */

    $app->group(['prefix' => 'Solicitudes'], function() use ($app) {
        $app->get('all', 'ApplicationsController@all');
        $app->get('all/Clients', 'ApplicationsController@ClientsToCredit');
        $app->post('add','ApplicationsController@add');
        $app->get('show/{id}', 'ApplicationsController@show');
        $app->put('update/{id}', 'ApplicationsController@update');
        $app->delete('delete/{id}', 'ApplicationsController@delete');
        $app->get('report/{id}', 'ApplicationsController@report');

        $app->get('all/AvalCredito', 'CreditaidsController@all');
        $app->post('add/AvalCredito','CreditaidsController@add');
        //$app->get('show/{id}/AvalCredito', 'CreditaidsController@show');
        $app->put('update/{id}/AvalCredito', 'CreditaidsController@update');
        $app->delete('delete/{id}/AvalCredito', 'CreditaidsController@delete');
        $app->get('report/{id}/AvalCredito', 'CreditaidsController@report');

        //$app->get('all/FilesApplication', 'FilesController@all');
        $app->post('add/FilesApplication','FilesController@add');
        $app->get('show/{id}/FilesApplication', 'FilesController@ReturnFile');

        $app->get('/all/Credits', 'CreditsController@allCreditApproved');
        $app->get('show/{id}/Credits', 'CreditsController@showCreditApproved');
        $app->post('add/Credits', 'CreditsController@addCreditApproved');
        $app->post('add/pay/Credits','CreditsController@addCreditPay');
        $app->put('credits/{id}/Credits', 'CreditsController@put');
        $app->delete('credits/{id}/Credits', 'CreditsController@remove');
    });




    /**
     * Routes for resource credits
     */
    $app->group(['prefix' => 'Creditos'], function() use ($app) {
        $app->get('/all', 'CreditsController@allCreditApproved');
        $app->get('show/{id}', 'CreditsController@showCreditApproved');
        $app->post('add', 'CreditsController@addCreditApproved');
        $app->post('add/pay','CreditsController@addCreditPay');
        $app->put('credits/{id}', 'CreditsController@put');
        $app->put('update/{appId}/Liquidar','CreditsController@liquidate');
        $app->delete('credits/{id}', 'CreditsController@remove');
        $app->delete('delete/{appId}/LastMove','CreditsController@deleteLastMove');

        $app->get('all/Solicitudes', 'ApplicationsController@all');
        $app->get('all/Clients/Solicitudes', 'ApplicationsController@ClientsToCredit');
        $app->post('add/Solicitudes','ApplicationsController@add');
        $app->get('show/{id}/Solicitudes', 'ApplicationsController@show');
        $app->put('update/{id}/Solicitudes', 'ApplicationsController@update');
        $app->delete('delete/{id}/Solicitudes', 'ApplicationsController@delete');
        $app->get('report/{id}/Solicitudes', 'ApplicationsController@report');



        $app->post('add/FilesApplication', 'FilesController@add');
        $app->get('show/{id}/FilesApplication', 'FilesController@ReturnFile');

        $app->put('update/{idCredit}/ControlCredits/{idFile}','CreditsController@updateCreditFile');
    });


    /**
     * Routes for resource stockholder
     */
    $app->group(['prefix' => 'Fondeadores'], function() use ($app) {
        $app->get('all', 'StockholdersController@allStockholder');
        $app->get('show/{id}', 'StockholdersController@showStockholder');
        $app->post('add', 'StockholdersController@createStockholder');
        $app->put('update/{id}', 'StockholdersController@updateStockholder');
        $app->delete('delete/{id}', 'StockholdersController@deleteStockholder');

        $app->get('all/managers', 'StockholdersController@allManager');
        $app->get('show/{id}/managers', 'StockholdersController@showManager');
        $app->post('add/managers', 'StockholdersController@createManager');
        $app->put('update/{id}/managers', 'StockholdersController@updateManager');
        $app->delete('delete/{id}/managers', 'StockholdersController@deleteManager');

        $app->get('all/account', 'StockholdersController@allAccount');
        $app->get('show/{id}/account', 'StockholdersController@showAccount');
        $app->post('add/account', 'StockholdersController@createAccount');
        $app->put('update/{id}/account', 'StockholdersController@updateAccount');
        $app->delete('delete/{id}/account', 'StockholdersController@deleteAccount');

        $app->get('all/fund/{id}', 'CreditStockholdersController@getFundsByIDStockholder');
        $app->get('show/{idStock}/fund/{id}', 'CreditStockholdersController@getCtrlByIDStockholder');
        $app->post('add/fund', 'CreditStockholdersController@CreateFund');
        $app->post('add/fundcntrl', 'CreditStockholdersController@CreateCntrl');
        $app->put('update/{id}/fund', 'CreditStockholdersController@CreateCntrl');
        $app->delete('delete/{id}/fund', 'CreditStockholdersController@deleteAccount');

        $app->post('add/Files_Stock','FilesStockController@add');
        $app->get('show/{id}/Files_Stock','FilesStockController@ReturnFile');

        $app->put('update/{idControlFund}/Control_Fund/{idFile}','CreditStockholdersController@updateControlFundFile');
        $app->put('update/{idFund}/Fund/{idFile}','CreditStockholdersController@updateFundFile');
    });



    /**
     * Routes for resource dashboard
     */
    $app->group(['prefix' => 'Dashboard'], function() use ($app) {
        $app->get('show/Morosidad', 'DashboardsController@MorosidadTotal');
        $app->get('show/InteresNeto', 'DashboardsController@InteresesNeto');
        $app->post('dashboard', 'DashboardsController@add');
        $app->put('dashboard/{id}', 'DashboardsController@put');
        $app->delete('dashboard/{id}', 'DashboardsController@remove');
    });
});

