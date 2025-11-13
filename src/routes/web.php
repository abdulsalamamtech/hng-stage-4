<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});



Route::get('/artisan', function () {
    Artisan::call('inspire');
    Artisan::call('optimize:clear');
    Artisan::call('migrate');
    Artisan::call('db:seed');
    Artisan::call('optimize');
    Artisan::call('inspire');
    return "Artisan commands executed successfully.";
});

Route::get('/fresh', function () {
    Artisan::call('inspire');
    Artisan::call('optimize:clear');
    Artisan::call('migrate:fresh');
    Artisan::call('db:seed');
    Artisan::call('optimize');
    Artisan::call('inspire');
    return "Database refreshed successfully.";
});


Route::get('/run', function (Request $request) {
    if (!$request->filled('query')) {
        return "No query parameter provided.";
    }
    $query = $request->input('query');
    Artisan::call($query);
    Artisan::call('inspire');
    return "command executed";
});
