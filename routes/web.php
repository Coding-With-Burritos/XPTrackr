<?php

use Illuminate\Support\Facades\Route;

// Home route - guests see welcome, authenticated users redirect to dashboard
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('welcome');
});

// Dashboard route - authenticated users only
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');
