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

Route::post('/login', 'CustomAuth\LoginController@login')->name('login');
Route::post('/signup', 'CustomAuth\SignupController@signup')->name('register');
Route::prefix('/api/v1')->group(function () {
    Route::get('/users/millionaires', 'User\UserController@getMillionaires');
    Route::get('/matches/{match_id}/comments', 'Comment\CommentController@getComments');
});

//Route::middleware('auth:api')->group(function () { //This uses 'auth' middleware (default authentication, defined in Kernel.php) and 'api' guard, we use 'jwt.auth' so don't use this

//Routes only access with authenticated jwt tokens
Route::middleware('jwt.auth')->group(function () {

    Route::get('/logout', 'CustomAuth\LogoutController@logout')->name('logout');

    Route::prefix('/api/v1')->group(function () {

        //Admin Only
        Route::middleware(['admin'])->group(function () {
            Route::get('/users', 'User\UserController@getUsers');
        });

        //User Only
        Route::middleware(['user'])->group(function () {
            Route::put('/users/{user_id}', 'User\UserController@updateUser');
            Route::post('/users/{user_id}/balance', 'Transaction\TransactionController@deposit');
            Route::post('/matches/{match_id}/comments', 'Comment\CommentController@postComment');
            Route::post('/matches/{match_id}/bets', 'Bet\BetController@betMatch');
            Route::get('/matches/{match_id}/bets', 'Bet\BetController@getUserBetsForMatch');
            Route::get('/users/{user_id}/bets', 'Bet\BetController@getBets');
        });
    });
});