<?php

use App\Http\Controllers\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ConversationController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    // Routes pour les conversations
    Route::get('conversations', [ConversationController::class, 'index']);

    Route::get('conversations/{id}', [ConversationController::class, 'show']);

    Route::post('conversations', [ConversationController::class, 'store']);

    // Routes pour les messages
    Route::get('messages/{conversationId}', [MessageController::class, 'index']);

    Route::post('messages', [MessageController::class, 'store']);



    Route::post('logout', [AuthenticationController::class, 'logout']);
});

Route::post('register', [AuthenticationController::class, 'store']);
Route::post('login', [AuthenticationController::class, 'login'])->name('login');