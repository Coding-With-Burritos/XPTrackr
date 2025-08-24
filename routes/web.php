<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');
});


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
});
