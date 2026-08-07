<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:5,1');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post')->middleware('throttle:5,1');
Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('verify-otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify-otp.post')->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (all authenticated + active)
Route::middleware(['auth', 'active'])->group(function () {

    // Dashboard — all authenticated users
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // --- POS & Sales ---
    Route::middleware('can:access pos')->group(function () {
        Route::get('pos', [POSController::class, 'index'])->name('pos.index');
        Route::get('pos/search', [POSController::class, 'search'])->name('pos.search');
        Route::post('pos/store', [POSController::class, 'store'])->name('pos.store');
        Route::get('pos/invoice/{sale}', [POSController::class, 'invoice'])->name('pos.invoice');
    });

    Route::middleware('can:view sales')->group(function () {
        Route::get('sales', [POSController::class, 'salesIndex'])->name('sales.index');
    });

    // --- Medicines ---
    Route::middleware('can:view medicines')->group(function () {
        Route::get('medicines', [MedicineController::class, 'index'])->name('medicines.index');
    });
    Route::middleware('can:create medicines')->group(function () {
        Route::get('medicines/create', [MedicineController::class, 'create'])->name('medicines.create');
        Route::post('medicines', [MedicineController::class, 'store'])->name('medicines.store');
    });
    Route::middleware('can:edit medicines')->group(function () {
        Route::get('medicines/{medicine}/edit', [MedicineController::class, 'edit'])->name('medicines.edit');
        Route::put('medicines/{medicine}', [MedicineController::class, 'update'])->name('medicines.update');
    });
    Route::middleware('can:delete medicines')->group(function () {
        Route::delete('medicines/{medicine}', [MedicineController::class, 'destroy'])->name('medicines.destroy');
    });

    // --- Categories ---
    Route::middleware('can:view categories')->group(function () {
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    });
    Route::middleware('can:create categories')->group(function () {
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    });
    Route::middleware('can:edit categories')->group(function () {
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    });
    Route::middleware('can:delete categories')->group(function () {
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    // --- Suppliers ---
    Route::middleware('can:view suppliers')->group(function () {
        Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('suppliers/{supplier}/purchases', [SupplierController::class, 'purchases'])->name('suppliers.purchases');
    });
    Route::middleware('can:create suppliers')->group(function () {
        Route::post('suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    });
    Route::middleware('can:edit suppliers')->group(function () {
        Route::put('suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    });
    Route::middleware('can:delete suppliers')->group(function () {
        Route::delete('suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    });

    // --- Customers ---
    Route::middleware('can:view customers')->group(function () {
        Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('customers/{customer}/sales', [CustomerController::class, 'sales'])->name('customers.sales');
    });
    Route::middleware('can:create customers')->group(function () {
        Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');
    });
    Route::middleware('can:edit customers')->group(function () {
        Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    });
    Route::middleware('can:delete customers')->group(function () {
        Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    });

    // --- Purchases ---
    Route::middleware('can:view purchases')->group(function () {
        Route::get('purchases', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
    });
    Route::middleware('can:create purchases')->group(function () {
        Route::get('purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::post('purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    });

    // --- Expenses ---
    Route::middleware('can:view expenses')->group(function () {
        Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    });
    Route::middleware('can:create expenses')->group(function () {
        Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    });
    Route::middleware('can:edit expenses')->group(function () {
        Route::put('expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    });
    Route::middleware('can:delete expenses')->group(function () {
        Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    });

    // --- Reports ---
    Route::middleware('can:view reports')->group(function () {
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    });

    // --- Settings (Super Admin / Admin only) ---
    Route::middleware('can:view settings')->group(function () {
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    });
    Route::middleware('can:edit settings')->group(function () {
        Route::put('settings/{setting}', [SettingController::class, 'update'])->name('settings.update');
    });

    // --- Users (Super Admin only) ---
    Route::middleware('can:view users')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
    });
    Route::middleware('can:create users')->group(function () {
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
    });
    Route::middleware('can:edit users')->group(function () {
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });
    Route::middleware('can:delete users')->group(function () {
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});
