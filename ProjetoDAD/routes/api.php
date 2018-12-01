<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('items', 'ItemControllerAPI@index');

Route::post('login', 'LoginControllerAPI@login')->name('login');
  // rota logout só é acessivel se o utilizador  estiver logado -> middleware auth:api
Route::middleware('auth:api')->post('logout', 'LoginControllerAPI@logout');


// US2
Route::middleware(['auth:api','manager'])->post('users/registerWorker', 'UserControllerAPI@registerWorker');
Route::patch('users/confirmRegistration/{id}', 'UserControllerAPI@confirmRegistration');


//Rota para aquando o login a storage guarda o user autenticado
Route::middleware('auth:api')->get('users/me', 'UserControllerAPI@myProfile');

//US4
Route::middleware('auth:api')->patch('users/password/{id}','UserControllerAPI@changePassword');

//US6
Route::middleware('auth:api')->get('users/dateShift/{id}','UserControllerAPI@getCurrentShiftInformation');
Route::middleware('auth:api')->patch('users/startShift/{id}','UserControllerAPI@startShift');
Route::middleware('auth:api')->patch('/users/endShift/{id}','UserControllerAPI@endShift');

//US9
Route::middleware(['auth:api','isCook'])->get('users/orders/{id}','UserControllerAPI@getCookOrdersList');