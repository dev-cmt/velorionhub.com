<?php

use App\Http\Controllers\Auth\JwtAuthController;
use App\Http\Controllers\Api\AgentController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [JwtAuthController::class, 'register']);
Route::post('/login', [JwtAuthController::class, 'login']);

// Protected routes (JWT required)
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [JwtAuthController::class, 'logout']);
    Route::post('/refresh', [JwtAuthController::class, 'refresh']);
});

// Route::post('/token/check-courier', [AgentController::class, 'checkManualy'])->middleware('token.valid');

Route::post('/ai-agent', [AgentController::class, 'aiAgent']);

