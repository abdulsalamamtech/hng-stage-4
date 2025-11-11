<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// protected route
Route::middleware('auth:sanctum')->group(function () {
    // User resource routes
    Route::apiResource('users', UserController::class)
        ->only(['index', 'show', 'update']);
});
