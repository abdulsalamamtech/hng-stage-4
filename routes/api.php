<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');



// API Version 1 Routes
Route::prefix('v1')->group(function () {
    require __DIR__ . '/v1/api.php';
});
