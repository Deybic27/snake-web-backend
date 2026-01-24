<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// User authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// User routes
Route::middleware('auth:sanctum')->get('/user/stats', [UserController::class, 'getStats']);

// Game session routes
Route::middleware('auth:sanctum')->post('/game/session/start', [GameSessionController::class, 'start']);
Route::middleware('auth:sanctum')->patch('/game/session/{id}/complete', [GameSessionController::class, 'complete']);

// Game routes
Route::middleware('auth:sanctum')->post('/game', [GameController::class, 'create']);