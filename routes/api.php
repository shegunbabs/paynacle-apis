<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Services\AirtimeController;
use App\Http\Controllers\API\Services\DataController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(static function () {

    Route::prefix('auth')->group(static function () {
        Route::post('login', LoginController::class);
        Route::post('register', RegisterController::class);
    });

    Route::middleware('auth:sanctum')->group(static function(){

        Route::prefix('airtime')->group(function()
        {
            Route::get('providers', [AirtimeController::class, 'providers']);
            Route::post('purchase', [AirtimeController::class, 'purchase']);
        });

        Route::prefix('data')->group(function(){
            Route::get('providers', [DataController::class, 'providers']);
            Route::get('{provider}/bundles', [DataController::class, 'dataBundle']);
            Route::post('purchase', [DataController::class, 'purchase']);
        });


    });

});
