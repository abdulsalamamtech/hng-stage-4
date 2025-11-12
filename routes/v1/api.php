<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;




// index
Route::get('/users', [UserController::class, 'index']);

// store
Route::post('/users', [UserController::class, 'store']);

// show
Route::get('/users/{user:id}', [UserController::class, 'show']);

// update
Route::put('/users/{user:id}', [UserController::class, 'update']);

// delete
Route::delete('/users/{user:id}', [UserController::class, 'destroy']);
