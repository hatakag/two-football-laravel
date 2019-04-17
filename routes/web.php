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

//User
Route::get('/users', 'UserController@getUsers');
Route::put('/users/{id}', 'UserController@updateUser');
Route::post('/signup', 'SignupController@signup');
Route::post('/login', 'LoginController@login');

//Transaction
Route::post('/users/{id}/balance', 'TransactionController@deposit');