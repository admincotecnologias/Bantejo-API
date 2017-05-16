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
 $app->post('/', 'CreditsController@addCreditType');
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
    });
/**
 * Routes for resource Permission
 */
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
 $app->group(['prefix' => 'Paginas','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'PagesController@all');
     $app->post('add','PagesController@add');
     $app->get('show/{id}', 'PagesController@show');
     $app->put('update/{id}', 'PagesController@update');
     $app->delete('delete/{id}', 'PagesController@delete');
     $app->get('report/{id}', 'PagesController@report');
    });

/**
 * Routes for resource occupation
 */
 $app->group(['prefix' => 'Puestos','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'OccupationsController@all');
     $app->post('add','OccupationsController@add');
     $app->get('show/{id}', 'OccupationsController@show');
     $app->put('update/{id}', 'OccupationsController@update');
     $app->delete('delete/{id}', 'OccupationsController@delete');
     $app->get('report/{id}', 'OccupationsController@report');
    });

/**
 * Routes for resource employee
 */
  $app->group(['prefix' => 'Empleados','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'EmployeesController@all');
     $app->post('add','EmployeesController@add');
     $app->get('show/{id}', 'EmployeesController@show');
     $app->put('update/{id}', 'EmployeesController@update');
     $app->delete('delete/{id}', 'EmployeesController@delete');
     $app->get('report/{id}', 'EmployeesController@report');
    });

/**
 * Routes for resource banks
 */
  $app->group(['prefix' => 'Bancos','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'BanksController@all');
     $app->post('add','BanksController@add');
     $app->get('show/{id}', 'BanksController@show');
     $app->put('update/{id}', 'BanksController@update');
     $app->delete('delete/{id}', 'BanksController@delete');
     $app->get('report/{id}', 'BanksController@report');
    });

/**
 * Routes for resource clients
 */
 $app->group(['prefix' => 'Clientes','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'ClientsController@all');
     $app->post('add','ClientsController@add');
     $app->get('show/{id}', 'ClientsController@show');
     $app->put('update/{id}', 'ClientsController@update');
     $app->delete('delete/{id}', 'ClientsController@delete');
     $app->get('report/{id}', 'ClientsController@report');
    });

/**
 * Routes for resource client-banks
 */
 $app->group(['prefix' => 'BancosClientes','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'ClientBanksController@all');
     $app->post('add','ClientBanksController@add');
     $app->get('show/{id}', 'ClientBanksController@show');
     $app->put('update/{id}', 'ClientBanksController@update');
     $app->delete('delete/{id}', 'ClientBanksController@delete');
     $app->get('report/{id}', 'ClientBanksController@report');
    });

/**
 * Routes for resource client-shareholders
 */
 $app->group(['prefix' => 'AccionistasClientes','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'ClientShareholdersController@all');
     $app->post('add','ClientShareholdersController@add');
     $app->get('show/{id}', 'ClientShareholdersController@show');
     $app->put('update/{id}', 'ClientShareholdersController@update');
     $app->delete('delete/{id}', 'ClientShareholdersController@delete');
     $app->get('report/{id}', 'ClientShareholdersController@report');
    });

/**
 * Routes for resource applications
 */

 $app->group(['prefix' => 'Solicitudes','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'ApplicationsController@all');
     $app->post('add','ApplicationsController@add');
     $app->get('show/{id}', 'ApplicationsController@show');
     $app->put('update/{id}', 'ApplicationsController@update');
     $app->delete('delete/{id}', 'ApplicationsController@delete');
     $app->get('report/{id}', 'ApplicationsController@report');
    });

/**
 * Routes for resource creditaids
 */

 $app->group(['prefix' => 'AvalCredito','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'CreditaidsController@all');
     $app->post('add','CreditaidsController@add');
     $app->get('show/{id}', 'CreditaidsController@show');
     $app->put('update/{id}', 'CreditaidsController@update');
     $app->delete('delete/{id}', 'CreditaidsController@delete');
     $app->get('report/{id}', 'CreditaidsController@report');
    });


/**
 * Routes for resource files
 */
  $app->group(['prefix' => 'FilesApplication','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'FilesController@all');
     $app->post('add','FilesController@add');
     $app->get('show/{id}', 'FilesController@ReturnFile');
     $app->put('update/{id}', 'FilesController@update');
     $app->delete('delete/{id}', 'FilesController@delete');
     $app->get('report/{id}', 'FilesController@report');
    });

/**
 * Routes for resource files-clients
 */
 $app->group(['prefix' => 'FilesClient','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'FilesClientsController@all');
     $app->post('add','FilesClientsController@add');
     $app->get('show/{id}', 'FilesClientsController@ReturnFile');
     $app->put('update/{id}', 'FilesClientsController@update');
     $app->delete('delete/{id}', 'FilesClientsController@delete');
     $app->get('report/{id}', 'FilesClientsController@report');
    });

/**
 * Routes for resource managers
 */
  $app->group(['prefix' => 'Managers','middleware'=>'Api'], function() use ($app) {
     $app->get('all', 'ManagerclientsController@all');
     $app->post('add','ManagerclientsController@add');
     $app->get('show/{id}', 'ManagerclientsController@show');
     $app->delete('delete/{id}', 'ManagerclientsController@delete');
    });

/**
 * Routes for resource credits
 */
$app->group(['prefix' => 'Credits','middleware'=>'Api'], function() use ($app) {
    $app->get('/all', 'CreditsController@allCreditApproved');
    $app->get('show/{id}', 'CreditsController@showCreditApproved');
    $app->post('add', 'CreditsController@addCreditApproved');
    $app->post('add/pay','CreditsController@addCreditPay');
    $app->put('credits/{id}', 'CreditsController@put');
    $app->delete('credits/{id}', 'CreditsController@remove');
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
});


