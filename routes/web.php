<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return Inertia::render('Login');
    })->name('login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard', [
            'priority' => config('tasks.priority'),
            'status' => config('tasks.status'),
        ]);
    })->name('dashboard');
});
