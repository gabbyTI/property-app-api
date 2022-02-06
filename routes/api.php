<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\User\MeController;
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

Route::get('me', [MeController::class, 'getMe']);



// Route group for authenticated users only
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout', [LoginController::class, 'logout']);
    Route::post('account/delete', [LoginController::class, 'deleteAccount']);

    Route::post('properties', [PropertyController::class, 'CreateProperty']);
    Route::patch('properties/{property}', [PropertyController::class, 'updateProperty']);
    Route::delete('properties/{property}', [PropertyController::class, 'deleteProperty']);
});

// Route group for guest users only
Route::group(['middleware' => ['guest:api']], function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);

    Route::get('properties', [PropertyController::class, 'getProperties']);
    Route::get('properties/{property}', [PropertyController::class, 'getProperty']);
});
