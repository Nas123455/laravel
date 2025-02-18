<?php

use App\Http\Controllers\admin\LoginController as AdminLoginController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'account'], function() {

    // Guest routes for users
    Route::middleware('guest')->group(function() {
        Route::get('/login', [LoginController::class, 'index'])->name('account.login');
        Route::get('/register', [LoginController::class, 'register'])->name('account.register');
        Route::post('/process-register', [LoginController::class, 'processRegister'])->name('account.processRegister');
        Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('account.authenticate');
    });

    // Authenticated routes for users
    Route::middleware('auth')->group(function() {
        Route::get('/logout', [LoginController::class, 'logout'])->name('account.logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('account.dashboard');
    });
});

Route::group(['prefix' => 'admin'], function() {

    // Guest routes for admins
    Route::middleware('admin.guest')->group(function() {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });

    // Authenticated routes for admins
    Route::middleware('admin.auth')->group(function() {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    });
});
