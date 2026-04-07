<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/login'));

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// User routes
Route::middleware('auth')->prefix('')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/scan', [UserController::class, 'scan'])->name('user.scan');
    Route::post('/scan', [UserController::class, 'submitScan'])->name('user.scan.submit');
    Route::get('/scan/{scan}/result', [UserController::class, 'result'])->name('user.result');
    Route::get('/history', [UserController::class, 'history'])->name('user.history');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/models', [AdminController::class, 'models'])->name('admin.models');
    Route::post('/models', [AdminController::class, 'uploadModel'])->name('admin.models.upload');
    Route::patch('/models/{model}/activate', [AdminController::class, 'activateModel'])->name('admin.models.activate');
    Route::delete('/models/{model}', [AdminController::class, 'deleteModel'])->name('admin.models.delete');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/{user}/scans', [AdminController::class, 'userScans'])->name('admin.user.scans');
    Route::get('/scans', [AdminController::class, 'allScans'])->name('admin.scans');
});
