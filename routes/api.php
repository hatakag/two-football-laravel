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
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::post('/login', 'CustomAuth\LoginController@login');
Route::post('/signup', 'CustomAuth\SignupController@signup');

//Routes only access with authenticated users
Route::middleware('auth:api')->group(function () {

    //Routes only access with authenticated jwt tokens
    Route::middleware('jwt.auth')->group(function() {

        Route::get('/logout', 'CustomAuth\LogoutController@logout');

        Route::prefix('/api/v1')->group(function () {

            //Admin Only
            Route::middleware(['admin'])->group(function () {
                Route::get('/users', 'User\UserController@getUsers');
            });

            //User Only
            Route::middleware(['user'])->group(function () {
                Route::put('/users/{user_id}', 'User\UserController@updateUser');
                Route::post('/users/{user_id}/balance', 'Transaction\TransactionController@deposit');
            });
        });
    });
});