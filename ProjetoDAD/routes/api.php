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
Route::middleware('auth:api')->patch('users/password','UserControllerAPI@changePassword');