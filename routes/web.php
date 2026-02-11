<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Admin Routing
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Student Management
    Route::resource('students', StudentController::class);
    
    // Invoices Management
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    
    // Payment Action
    Route::post('/payments/{invoice}', [PaymentController::class, 'store'])->name('payments.store');
    Route::post('/payments/{payment}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
});

// Auth Placeholders
Route::get('/login', function() { return "Halaman Login"; })->name('login');
Route::post('/logout', function() { return "Proses Logout"; })->name('logout');