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

//US5
Route::middleware('auth:api')->put('users/{id}','UserControllerAPI@update');

//US6
Route::middleware('auth:api')->get('users/dateShift/{id}','UserControllerAPI@getCurrentShiftInformation');
Route::middleware('auth:api')->patch('users/startShift/{id}','UserControllerAPI@startShift');
Route::middleware('auth:api')->patch('/users/endShift/{id}','UserControllerAPI@endShift');

//US9
Route::middleware(['auth:api','isCook'])->get('users/orders/{id}','UserControllerAPI@getCookOrdersList');

//US11
Route::middleware(['auth:api','isCook'])->get('users/orders/all/{id}','UserControllerAPI@getCookAllOrdersList');


//tanto os coockers como os waiter precisam popr isso é que tirei o middleware
Route::middleware(['auth:api'])->patch('orders/state/{id}','OrderControllerAPI@updateState');

//US12
Route::middleware(['auth:api','isWaiter'])->get('meals/nonActiveTables', 'MealControllerAPI@getNonActiveTables');
Route::middleware(['auth:api','isWaiter'])->post('meals/createMeal', 'MealControllerAPI@createMeal');

//US13
Route::middleware(['auth:api','isWaiter'])->get('meals/myMeals/{id}', 'MealControllerAPI@getMyMeals');
Route::middleware(['auth:api','isWaiter'])->post('orders/createOrder', 'OrderControllerAPI@createOrder');
Route::middleware(['auth:api','isWaiter'])->post('meals/updateTotalPrice', 'MealControllerAPI@updateTotalPrice');


//US14
Route::middleware(['auth:api','isWaiter'])->get('user/myOrdersWaiter/{id}', 'UserControllerAPI@getMyOrdersWaiter');


//US15
Route::middleware(['auth:api','isWaiter'])->delete('orders/{id}', 'OrderControllerAPI@destroy');
