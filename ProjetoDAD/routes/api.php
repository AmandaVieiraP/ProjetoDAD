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
Route::middleware('auth:api')->put('users/update/{id}','UserControllerAPI@update');

//US6
Route::middleware('auth:api')->get('users/dateShift/{id}','UserControllerAPI@getCurrentShiftInformation');
Route::middleware('auth:api')->patch('users/startShift/{id}','UserControllerAPI@startShift');
Route::middleware('auth:api')->patch('/users/endShift/{id}','UserControllerAPI@endShift');

//US9
Route::middleware(['auth:api','isCook'])->get('users/orders/{id}','UserControllerAPI@getCookOrdersList');

//US11
Route::middleware(['auth:api','isCook'])->get('unsignedOrders','OrderControllerAPI@getUnsignedOrders');
Route::middleware(['auth:api','isCookOrWaiter'])->patch('orders/state/{id}','OrderControllerAPI@updateState');
Route::middleware(['auth:api','isCook'])->patch('orders/cooks/{id}','OrderControllerAPI@updateCook');


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


//US16
Route::middleware(['auth:api','isCook'])->get('orders/responsibleWaiter/{id}', 'OrderControllerAPI@getresponsibleWaiter');


//US17
Route::middleware(['auth:api','isWaiter'])->get('user/myPreparedOrdersWaiter/{id}', 'UserControllerAPI@getMyPreparedOrdersWaiter');

//US19
Route::middleware(['auth:api','isManagerOrWaiter'])->get('orders/ordersOfaMeal/{id}', 'OrderControllerAPI@getOrdersOfAMeal');


//US20
Route::middleware(['auth:api','isWaiter'])->post('meals/terminateMeal/{id}', 'MealControllerAPI@terminateMeal');
Route::middleware(['auth:api','isCookOrWaiter'])->get('meals/mealFromOrder/{id}', 'MealControllerAPI@getMealFromOrder');

//US21
Route::middleware(['auth:api','isWaiter'])->post('invoices/create/{id}', 'InvoiceControllerAPI@createInvoice');
Route::middleware(['auth:api','isWaiter'])->post('invoiceItems/create/{mealid}/{invoiceId}', 'InvoiceItemControllerAPI@createInvoiceItems');

//US28
Route::middleware(['auth:api','manager'])->get('tables', 'TableControllerAPI@index');
Route::middleware(['auth:api','manager'])->post('tables', 'TableControllerAPI@store');
Route::middleware(['auth:api','manager'])->put('tables/{id}', 'TableControllerAPI@update');
Route::middleware(['auth:api','manager'])->delete('tables/{id}', 'TableControllerAPI@destroy');
Route::middleware(['auth:api','manager'])->post('items', 'ItemControllerAPI@store');
Route::middleware(['auth:api','manager'])->get('items/{id}', 'ItemControllerAPI@show');
Route::middleware(['auth:api','manager'])->put('items/{id}', 'ItemControllerAPI@update');
Route::middleware(['auth:api','manager'])->delete('items/{id}', 'ItemControllerAPI@destroy');


//US22
Route::middleware(['auth:api','isCashierOrManager'])->get('invoices/pending', 'InvoiceControllerAPI@getPendingInvoicesWithWaiter');

Route::middleware(['auth:api','isCashier'])->post('invoices/pay/{id}', 'InvoiceControllerAPI@payInvoice');

Route::middleware(['auth:api','isCashierOrManager'])->get('invoices/getPdf/{id}', 'InvoiceControllerAPI@getInvoicePdf');

Route::middleware(['auth:api','isCashier'])->get('invoices/paid', 'InvoiceControllerAPI@getPaidInvoices');

// Route::middleware(['auth:api','isCashier'])->get('invoices/items/{id}', 'InvoiceControllerAPI@getInvoicesItems');

Route::middleware(['auth:api','isCashier'])->get('invoiceItems/items/{id}', 'InvoiceItemControllerAPI@getInvoicesItems');

//US29
Route::middleware(['auth:api','manager'])->get('users', 'UserControllerAPI@index');
Route::middleware(['auth:api','manager'])->get('user/{id}', 'UserControllerAPI@show');
Route::middleware(['auth:api','manager'])->patch('user/block/{id}', 'UserControllerAPI@blockUser');
Route::middleware(['auth:api','manager'])->patch('user/unBlock/{id}', 'UserControllerAPI@unBlockUser');

Route::post('user/blockedOrNot', 'UserControllerAPI@getUserByEmail');
Route::middleware(['auth:api','manager'])->delete('users/{id}', 'UserControllerAPI@destroy');


//US31
Route::middleware(['auth:api','manager'])->get('meals/activeOrTeminatedMeals', 'MealControllerAPI@getActiveOrTeminatedMeals');
