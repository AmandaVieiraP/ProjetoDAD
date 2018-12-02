<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});


Route::get('items', 'ItemControllerAPI@index');

Route::post('login', 'LoginControllerAPI@login')->name('login');
  // rota logout só é acessivel se o utilizador  estiver logado -> middleware auth:api
Route::middleware('auth:api')->post('logout', 'LoginControllerAPI@logout');

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