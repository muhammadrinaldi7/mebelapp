<?php

use App\Livewire\Dashboard;
use App\Livewire\BrandIndex;
use App\Livewire\CategoryIndex;
use App\Livewire\ProductIndex;
use App\Livewire\TransactionIn;
use App\Livewire\TransactionOut;
use App\Livewire\Sales;
use App\Livewire\UserManagement;
use App\Livewire\RoleManagement;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/products', ProductIndex::class)->name('products.index');
    Route::get('/brands', BrandIndex::class)->name('brands.index');
    Route::get('/categories', CategoryIndex::class)->name('categories.index');
    Route::get('/transactions/in', TransactionIn::class)->name('transactions.in');
    Route::get('/transactions/out', TransactionOut::class)->name('transactions.out');
    Route::get('/sales', Sales::class)->name('sales.index');

    // Admin only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/users', UserManagement::class)->name('users.index');
        Route::get('/roles', RoleManagement::class)->name('roles.index');
    });
});
