<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PdfController;


Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});
Route::middleware(['auth'])->group(function () {
    Route::resource('expenses', ExpenseController::class);
});
Route::resource('categories', CategoryController::class)->middleware('auth');
Route::resource('incomes', IncomeController::class)->middleware('auth');
Route::middleware(['auth'])->group(function () {
    Route::get('dashboard-finanzas', [DashboardController::class, 'index'])
        ->name('dashboard.finanzas');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/emails', [MailController::class, 'index'])->name('emails.index');
    Route::post('/emails/send', [MailController::class, 'send'])->name('emails.send');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/pdf/generar', [PdfController::class, 'generar'])
        ->name('pdf.generar');

});
Route::patch('/expenses/{expense}/toggle-deductible', [ExpenseController::class, 'toggleDeductible'])
    ->name('expenses.toggleDeductible');

require __DIR__.'/settings.php';
