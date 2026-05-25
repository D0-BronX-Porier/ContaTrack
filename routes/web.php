<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PdfController;

Route::view('/', 'welcome')->name('home');

/*
|--------------------------------------------------------------------------
| DASHBOARD BASE
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| EXPENSES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::resource('expenses', ExpenseController::class);
});

/*
|--------------------------------------------------------------------------
| CATEGORIES
|--------------------------------------------------------------------------
*/
Route::resource('categories', CategoryController::class)
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| INCOMES
|--------------------------------------------------------------------------
*/
Route::resource('incomes', IncomeController::class)
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| DASHBOARD FINANZAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('dashboard-finanzas', [DashboardController::class, 'index'])
        ->name('dashboard.finanzas');
});

/*
|--------------------------------------------------------------------------
| EMAILS (🔒 BLOQUEADO PARA CLIENTES)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/emails', function () {

        if (auth()->user()->role === 'cliente') {
            abort(403, 'No tienes acceso a los correos');
        }

        return app(MailController::class)->index();

    })->name('emails.index');

    Route::post('/emails/send', function () {

        if (auth()->user()->role === 'cliente') {
            abort(403, 'No tienes acceso a esta acción');
        }

        return app(MailController::class)->send(request());

    })->name('emails.send');
});

/*
|--------------------------------------------------------------------------
| PDF
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/pdf/generar', [PdfController::class, 'generar'])
        ->name('pdf.generar');

});

/*
|--------------------------------------------------------------------------
| EXTRA ACTION
|--------------------------------------------------------------------------
*/
Route::patch('/expenses/{expense}/toggle-deductible', [ExpenseController::class, 'toggleDeductible'])
    ->name('expenses.toggleDeductible');

/*
|--------------------------------------------------------------------------
| SETTINGS
|--------------------------------------------------------------------------
*/
require __DIR__ . '/settings.php';