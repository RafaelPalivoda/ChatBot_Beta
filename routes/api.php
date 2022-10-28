<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\Event\EventController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\UserController;
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

Route::group(['middleware'=>['auth:sanctum']], function(){
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/user/logged', [UserController::class, 'userLogged'])->name('user.userLogged');

    Route::get('/messages/{user}', [MessageController::class, 'listMessages'])->name('messages.listMessages');
    Route::post('/messages/store', [MessageController::class, 'store'])->name('messages.store');
});

Route::get('/whatsapp', [ApiController::class, 'index']);

Route::post('/whatsapp', [ApiController::class, 'index']);
