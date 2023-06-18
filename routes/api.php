<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UsersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1', 'as' => 'api.'], function () {

    Route::post('/login', [UsersController::class, 'login']);

    // Unathenticated Route
    Route::get('/users', [UsersController::class, 'index']);

    // Authenticate Routes
    Route::group([
        'middleware' => 'auth'
    ], function () {

        Route::post('/user/store', [UsersController::class, 'store']);

        Route::put('/user/{id}/update', [UsersController::class, 'update']);

        Route::delete('/user/{id}/delete', [UsersController::class, 'delete']);

    })->middleware('auth.session');
});
