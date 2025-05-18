<?php

use App\Http\Controllers\API\Auth\AuthApiController;
use App\Http\Controllers\Api\RuteApiController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransController;


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


Route::post('register', [AuthApiController::class, 'register']);
Route::post('login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthApiController::class, 'logout']);
    Route::get('users', [AuthApiController::class, 'userProfile']);
    Route::post('/search-rute', [RuteApiController::class, 'search']);
    Route::post('/dropdown-options', [RuteApiController::class, 'dropdownOptions']);
});

Route::post('/midtrans/create-transaction', [MidtransController::class, 'createTransaction']);
Route::post('/midtrans/callback', [MidtransController::class, 'paymentCallback']);

Route::get('user',[UserController::class,'index']);

// routes/api.php



