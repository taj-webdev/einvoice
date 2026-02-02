<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\InvoiceController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

// LOGIN
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// REGISTER
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

// LOGOUT
Route::get('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth.manual')->group(function () {

    /* ================= DASHBOARD ================= */
    Route::get('/dashboard', [DashboardController::class, 'index']);

    /* ================= CUSTOMERS ================= */
    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index']);
        Route::get('/create', [CustomerController::class, 'create']);
        Route::post('/store', [CustomerController::class, 'store']);
        Route::get('/edit/{id}', [CustomerController::class, 'edit']);
        Route::post('/update/{id}', [CustomerController::class, 'update']);
        Route::get('/delete/{id}', [CustomerController::class, 'destroy']);
    });

    /* ================= SETTINGS (ADMIN ONLY) ================= */
    Route::middleware('role.admin')->prefix('settings')->group(function () {
        Route::get('/', [SettingController::class, 'index']);
        Route::post('/store', [SettingController::class, 'store']);
        Route::post('/update/{id}', [SettingController::class, 'update']);
        Route::get('/delete/{id}', [SettingController::class, 'destroy']);
    });

    /* ================= INVOICES (FINANCE & ADMIN) ================= */
    Route::prefix('invoices')->group(function () {

        Route::get('/', [InvoiceController::class, 'index']);
        Route::get('/create', [InvoiceController::class, 'create']);
        Route::post('/store', [InvoiceController::class, 'store']);
        Route::get('/show/{id}', [InvoiceController::class, 'show']);
        Route::get('/edit/{id}', [InvoiceController::class, 'edit']);
        Route::post('/update/{id}', [InvoiceController::class, 'update']);
        Route::get('/delete/{id}', [InvoiceController::class, 'destroy']);

        // 🔥 TOGGLE PAID / UNPAID
        Route::get('/status/{id}', [InvoiceController::class, 'toggleStatus']);

        // 🖨️ CETAK INVOICE (PDF)
        Route::get('/print/{id}', [InvoiceController::class, 'printInvoice']);

        // 🚚 CETAK SURAT JALAN (PDF)
        Route::get('/surat-jalan/{id}', [InvoiceController::class, 'printSuratJalan']);

        // 📊 LAPORAN
        Route::get('/report/pdf', [InvoiceController::class, 'reportPdf']);
        Route::get('/report/excel', [InvoiceController::class, 'reportExcel']);
    });
});
