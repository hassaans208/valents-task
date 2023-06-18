<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UsersController;

// api version one routes implemented and tested
Route::group(['prefix' => 'v1', 'as' => 'api.'], function () {

    // created this route so the tester could authenticate and test the routes
    Route::post('/login', [UsersController::class, 'login']);

    // Unathenticated Route
    Route::get('/users', [UsersController::class, 'index']);

    // Authenticated Routes
    Route::group(['middleware' => 'auth:api'], function () {

        Route::post('/user/store', [UsersController::class, 'store']);

        Route::post('/user/{id}/update', [UsersController::class, 'update']);

        Route::delete('/user/{id}/delete', [UsersController::class, 'delete']);

        // created this route so the tester could logout and test other users
        Route::post('/logout', [UsersController::class, 'logout']);

    });
});
