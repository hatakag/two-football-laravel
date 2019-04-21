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

Route::get('/', function () {
    return view('welcome');
});

//Auth
Auth::routes();

//Routes only access with authenticated users
Route::middleware('auth')->group(function () {

    //Common route
    Route::get('/home', 'HomeController@index')->name('home');

    //Admin Only
    Route::middleware(['admin'])->group(function () {
        Route::get('/users', 'User\UserController@getUsers');
    });

    //Admin & User Only
    Route::middleware(['user'])->group(function () {
        Route::put('/users/{id}', 'User\UserController@updateUser');
        Route::post('/users/{id}/balance', 'Transaction\TransactionController@deposit');
    });

});