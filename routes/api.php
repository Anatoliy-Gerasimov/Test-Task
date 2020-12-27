<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group([
    'as' => 'api'
], function() {

    Route::group([
        'as' => '.user'
    ], function() {
        Route::get('users', [\App\Http\Controllers\Api\UserController::class, 'users'])->name('.list');
        Route::get('user/{user}', [\App\Http\Controllers\Api\UserController::class, 'details'])->name('.detail');
    });
});

