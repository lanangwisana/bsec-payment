<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// student routing
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    
    // Route sementara agar tidak error (bisa diarahkan ke dashboard dulu)
    Route::get('/invoices', [StudentController::class, 'dashboard'])->name('invoices.index');
    Route::get('/profile', [StudentController::class, 'dashboard'])->name('profile');
    Route::get('/payments/history', [StudentController::class, 'dashboard'])->name('payments.history');
    Route::get('/notifications/check', [StudentController::class, 'dashboard'])->name('notifications.check');
});