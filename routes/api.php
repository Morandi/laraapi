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

// Rotte utilizzate per l'autenticazione con JWT
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    // Schema della rotta: /middleware/prefix/rotta
    Route::post('login', 'AuthController@login');// api/auth/login
    Route::post('signup', 'AuthController@signup');// api/auth/signup
    Route::post('logout', 'AuthController@logout');// api/auth/logout
    Route::post('refresh', 'AuthController@refresh');// api/auth/refresh
    Route::post('me', 'AuthController@me'); // api/auth/me

});


Route::group([

    'middleware' => 'jwt.auth'

], function ($router) {

    Route::resource('users', 'UsersController');

});