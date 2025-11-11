<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// API Version 1 Routes
Route::prefix('v1')->group(function () {
    require __DIR__ . '/v1/api.php';
});


// Auth Routes
Route::prefix('auth')->group(function () {
    // login
    Route::post('/login', [AuthController::class, 'login']);
    // register
    Route::post('/register', [AuthController::class, 'register']);
    // logout
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
    // logout from all devices
    Route::middleware('auth:sanctum')->post('/logout-devices', [AuthController::class, 'logoutDevices']);
});


// test route
Route::get('/test', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'Test route working',
        'data' => $request->all()
    ], 200);
});


// health check route
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is healthy',
        'data' => [
            'status' => 'OK',
            'timestamp' => now()
        ]
    ], 200);
});
