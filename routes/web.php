<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', 'AuthController@getAuthCode')->name('login');
Route::get('/auth', 'AuthController@auth');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index')->name('index');
    Route::get('/mixin', function () {
        return view('mixin');
    });
});