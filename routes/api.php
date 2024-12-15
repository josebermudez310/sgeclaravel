<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');
    Route::post('register', 'App\Http\Controllers\AuthController@register');



});




// Rutas protegidas por JWT
Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('users', UserController::class);
});


use App\Http\Controllers\SalaController;

// Rutas protegidas por JWT
Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('salas', SalaController::class);
});



// Rutas protegidas por JWT
Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('eventos', EventoController::class);
});


Route::post('update-password/{id}', [AuthController::class, 'updatePasswordById']);
Route::post('/users/{id}/upload-images', [UserController::class, 'uploadImages']);
