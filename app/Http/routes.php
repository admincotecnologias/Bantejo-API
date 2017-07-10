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
 $app->get('/fund/{id}', 'CreditStockholdersController@getFundsByIDStockholder');
 $app->get('/stock', 'DashboardsController@MorosidadTotal');
 $app->group(['prefix' => 'Auth'], function() use ($app) {
     $app->post('LogIn', 'Api_authsController@LogIn');
     $app->get('LogOut','Api_authsController@LogOut');
    });
    $app->group(['prefix' => 'Roles','middleware'=>'Api'], function() use ($app) {
     $app->get('/', 'Api_authsController@checkAuth');
    });
    /**
 * Routes for resource User
 */
 $app->group(['prefix' => 'Usuarios','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'UserController@all');
     $app->post('add','UserController@add');
     $app->get('show/{id}', 'UserController@show');
     $app->put('update/{id}', 'UserController@update');
     $app->delete('delete/{id}', 'UserController@delete');
     $app->get('report/{id}', 'UserController@report');

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
/**
 * Routes for resource Permission
 */

/*
 $app->group(['prefix' => 'Permisos','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'PermissionsController@all');
     $app->post('add','PermissionsController@add');
     $app->get('show/{id}', 'PermissionsController@show');
     $app->put('update/{id}', 'PermissionsController@update');
     $app->delete('delete/{id}', 'PermissionsController@delete');
     $app->get('report/{id}', 'PermissionsController@report');
    });
/**
 * Routes for resource Page
 */

/*
 $app->group(['prefix' => 'Paginas','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'PagesController@all');
     $app->post('add','PagesController@add');
     $app->get('show/{id}', 'PagesController@show');
     $app->put('update/{id}', 'PagesController@update');
     $app->delete('delete/{id}', 'PagesController@delete');
     $app->get('report/{id}', 'PagesController@report');
    });
*/

/**
 * Routes for resource occupation
 */

/*
 $app->group(['prefix' => 'Puestos','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'OccupationsController@all');
     $app->post('add','OccupationsController@add');
     $app->get('show/{id}', 'OccupationsController@show');
     $app->put('update/{id}', 'OccupationsController@update');
     $app->delete('delete/{id}', 'OccupationsController@delete');
     $app->get('report/{id}', 'OccupationsController@report');
    });
/*

/**
 * Routes for resource employee
 */
/*
  $app->group(['prefix' => 'Empleados','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'EmployeesController@all');
     $app->post('add','EmployeesController@add');
     $app->get('show/{id}', 'EmployeesController@show');
     $app->put('update/{id}', 'EmployeesController@update');
     $app->delete('delete/{id}', 'EmployeesController@delete');
     $app->get('report/{id}', 'EmployeesController@report');
    });
*/

/**
 * Routes for resource banks
 */

/*
  $app->group(['prefix' => 'Bancos','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'BanksController@all');
     $app->post('add','BanksController@add');
     $app->get('show/{id}', 'BanksController@show');
     $app->put('update/{id}', 'BanksController@update');
     $app->delete('delete/{id}', 'BanksController@delete');
     $app->get('report/{id}', 'BanksController@report');
    });
*/

/**
 * Routes for resource clients
 */
 $app->group(['prefix' => 'Clientes','middleware'=>'Api'], function() use ($app) {
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

     //$app->get('all/FilesClient', 'FilesClientsController@all');
     $app->post('add/FilesClient','FilesClientsController@add');
     $app->get('show/{id}/FilesClient', 'FilesClientsController@ReturnFile');
     //$app->put('update/{id}/FilesClient', 'FilesClientsController@update');
     $app->delete('delete/{id}/FilesClient', 'FilesClientsController@DeleteFile');
     //$app->get('report/{id}/FilesClient', 'FilesClientsController@report');

     $app->get('all/Managers', 'ManagerclientsController@all');
     $app->post('add/Managers','ManagerclientsController@add');
     $app->get('show/{id}/Managers', 'ManagerclientsController@show');
     $app->delete('delete/{id}/Managers', 'ManagerclientsController@delete');

     $app->get('all/Applications','ApplicationsController@all');
     $app->get('all/Credits','CreditsController@allCreditApproved');


    });

/**
 * Routes for resource client-banks
 */

/*
 $app->group(['prefix' => 'BancosClientes','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'ClientBanksController@all');
     $app->post('add','ClientBanksController@add');
     $app->get('show/{id}', 'ClientBanksController@show');
     $app->put('update/{id}', 'ClientBanksController@update');
     $app->delete('delete/{id}', 'ClientBanksController@delete');
     $app->get('report/{id}', 'ClientBanksController@report');
    });
*/

/**
 * Routes for resource client-shareholders
 */

/*

 $app->group(['prefix' => 'AccionistasClientes','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'ClientShareholdersController@all');
     $app->post('add','ClientShareholdersController@add');
     $app->get('show/{id}', 'ClientShareholdersController@show');
     $app->put('update/{id}', 'ClientShareholdersController@update');
     $app->delete('delete/{id}', 'ClientShareholdersController@delete');
     $app->get('report/{id}', 'ClientShareholdersController@report');
    });

*/

/**
 * Routes for resource applications
 */

 $app->group(['prefix' => 'Solicitudes','middleware'=>'Api'], function() use ($app) {
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
 * Routes for resource creditaids
 */


/*
 $app->group(['prefix' => 'AvalCredito','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'CreditaidsController@all');
     $app->post('add','CreditaidsController@add');
     $app->get('show/{id}', 'CreditaidsController@show');
     $app->put('update/{id}', 'CreditaidsController@update');
     $app->delete('delete/{id}', 'CreditaidsController@delete');
     $app->get('report/{id}', 'CreditaidsController@report');
    });

*/


/**
 * Routes for resource files
 */

/*
  $app->group(['prefix' => 'FilesApplication','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'FilesController@all');
     $app->post('add','FilesController@add');
     $app->get('show/{id}', 'FilesController@ReturnFile');
     $app->put('update/{id}', 'FilesController@update');
     $app->delete('delete/{id}', 'FilesController@delete');
     $app->get('report/{id}', 'FilesController@report');
    });


 */


/**
 * Routes for resource files-clients
 */

/*
 $app->group(['prefix' => 'FilesClient','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'FilesClientsController@all');
     $app->post('add','FilesClientsController@add');
     $app->get('show/{id}', 'FilesClientsController@ReturnFile');
     $app->put('update/{id}', 'FilesClientsController@update');
     $app->delete('delete/{id}', 'FilesClientsController@delete');
     $app->get('report/{id}', 'FilesClientsController@report');
    });
*/


/**
 * Routes for resource managers
 */

/*
  $app->group(['prefix' => 'Managers','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'ManagerclientsController@all');
     $app->post('add','ManagerclientsController@add');
     $app->get('show/{id}', 'ManagerclientsController@show');
     $app->delete('delete/{id}', 'ManagerclientsController@delete');
    });

*/

/**
 * Routes for resource credits
 */
$app->group(['prefix' => 'Creditos','middleware'=>'Api'], function() use ($app) {
    $app->get('/all', 'CreditsController@allCreditApproved');
    $app->get('show/{id}', 'CreditsController@showCreditApproved');
    $app->post('add', 'CreditsController@addCreditApproved');
    $app->post('add/pay','CreditsController@addCreditPay');
    $app->put('credits/{id}', 'CreditsController@put');
    $app->delete('credits/{id}', 'CreditsController@remove');

    $app->get('all/Solicitudes', 'ApplicationsController@all');
    $app->get('all/Clients/Solicitudes', 'ApplicationsController@ClientsToCredit');
    $app->post('add/Solicitudes','ApplicationsController@add');
    $app->get('show/{id}/Solicitudes', 'ApplicationsController@show');
    $app->put('update/{id}/Solicitudes', 'ApplicationsController@update');
    $app->delete('delete/{id}/Solicitudes', 'ApplicationsController@delete');
    $app->get('report/{id}/Solicitudes', 'ApplicationsController@report');


    $app->post('add/FilesApplication', 'FilesController@add');
    $app->get('show/{id}/FilesApplication', 'FilesController@ReturnFile');


});


/**
 * Routes for resource stockholder
 */
$app->group(['prefix' => 'Fondeadores','middleware'=>'Api'], function() use ($app) {
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
$app->group(['prefix' => 'Dashboard','middleware'=>'Api'], function() use ($app) {
    $app->get('show/Morosidad', 'DashboardsController@MorosidadTotal');
    $app->get('show/InteresNeto', 'DashboardsController@InteresesNeto');
    $app->post('dashboard', 'DashboardsController@add');
    $app->put('dashboard/{id}', 'DashboardsController@put');
    $app->delete('dashboard/{id}', 'DashboardsController@remove');
});