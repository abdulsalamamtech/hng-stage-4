<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// user login route
// Route::post('/login', [AuthController::class, 'login']);
// user registration route
// Route::post('/register', [AuthController::class, 'register']);
// protected route
Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/profile', [AuthController::class, 'profile']);
    // Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/test', function (Request $request) {
        return $request->all();
    });
});
