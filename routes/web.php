<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// student routing
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/invoices', [StudentController::class, 'invoices'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [StudentController::class, 'showInvoice'])->name('invoices.show');
    Route::get('/invoices/{invoice}/download', [StudentController::class, 'dashboard'])->name('invoices.download');
    
    Route::post('/payments/{invoice}', [StudentController::class, 'storePayment'])->name('payments.store');
    
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
    Route::get('/payments/history', [StudentController::class, 'paymentHistory'])->name('payments.history');
    Route::get('/notifications/check', [StudentController::class, 'dashboard'])->name('notifications.check');
});

// Auth & Admin Placeholders agar tidak error
Route::get('/login', function() { return "Halaman Login"; })->name('login');
Route::post('/logout', function() { return "Proses Logout"; })->name('logout');
Route::get('/admin/dashboard', function() { return "Admin Dashboard"; })->name('admin.dashboard');