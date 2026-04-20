<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\AuthController;

// Public Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Must be logged in to see these)
Route::middleware('auth')->group(function () {
    
    // Customer
    Route::get('/', function () { return view('customer'); });
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');

   // Admin
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/order/{id}/update', [AdminController::class, 'updateStatus'])->name('admin.order.update');
    Route::post('/admin/driver/add', [AdminController::class, 'addDriver'])->name('admin.driver.add');
    
    // Driver
    Route::get('/driver', [DriverController::class, 'dashboard'])->name('driver.dashboard'); // Update this line!
    Route::post('/driver/order/{id}/proof', [DriverController::class, 'uploadProof'])->name('driver.order.proof');
});