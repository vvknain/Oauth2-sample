<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!


Route::get('/', function () {
    return view('welcome');
});
|
*/

Route::get('/', 'AuthController@show');
Route::get('/auth', 'AuthController@auth');
Route::post('/signin', 'AuthController@signin');
Route::post('/token', 'AuthController@exchange_code_for_token');
