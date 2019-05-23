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
/*
Route::get('/', function () {
    return view('welcome');
});
*/
/*
//Auth routes are created by `php artisan make:auth`
Auth::routes();
*/
/*
//Common route
Route::get('/home', 'HomeController@index')->name('home');
*/
Route::get('/test_bet_pusher', function () {
    return view('pusher');
});
Route::get('/test_comment_pusher', function () {
    return view('pusher2');
});