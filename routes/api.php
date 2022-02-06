<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UserController;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['prefix' => 'v1'], function(){
    Route::group(['prefix' => 'user'], function(){
        Route::post('login', [UserController::class, 'login']);
        // Route::post('register', [UserController::class, 'register']);
        Route::post('forgot-password', [UserController::class, 'forgot_password']);
        Route::get('notification', [UserController::class, 'cron_send_push_notification']);

        Route::get('profile', [UserController::class, 'profile']);
        Route::post('update', [UserController::class, 'updateUserDetails']);
        Route::post('change-password', [UserController::class, 'changePassword']);
        Route::post('logout', [UserController::class, 'logout']);

        Route::post('device-token', [UserController::class, 'device_token']);
        Route::post('notification-status', [UserController::class, 'notification_status']);
        Route::get('notification-history', [UserController::class, 'notification_history']);

        Route::get('full-sync', [UserController::class, 'fullSyncJsonFile']);
        Route::post('smart-sync', [UserController::class, 'smartSync']);
        Route::post('smart-sync-table', [UserController::class, 'smartSyncTable']);
        Route::post('smart-sync-table-demo', [UserController::class, 'smartSyncTableDemo']);

        Route::post('track-record', [UserController::class, 'trackRecord']);

        Route::post('check-status', [UserController::class, 'checkStatus']);
    });
});