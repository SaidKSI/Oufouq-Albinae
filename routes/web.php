<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'submit'])->name('submit.login');

Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    // ? Supplier Routes
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::post('/supplier/store', [SupplierController::class, 'store'])->name('supplier.store');
    Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
    // ? Client Routes
    Route::get('/client', [ClientController::class, 'index'])->name('client.index');
    Route::post('/client/store', [ClientController::class, 'store'])->name('client.store');
    Route::put('/client/{id}', [ClientController::class, 'update'])->name('client.update');
    Route::delete('/client/{id}', [ClientController::class, 'destroy'])->name('client.destroy');
    // ? Project Routes
    Route::get('/project', [ProjectController::class, 'index'])->name('project.index');
    Route::post('/project/store', [ProjectController::class, 'store'])->name('project.store');
    Route::put('/project/{id}', [ProjectController::class, 'update'])->name('project.update');
    Route::delete('/project/{id}', [ProjectController::class, 'destroy'])->name('project.destroy');
    // ? Order Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
    Route::put('/order/{id}', [OrderController::class, 'update'])->name('order.update');
    Route::delete('/order/{id}', [OrderController::class, 'destroy'])->name('order.destroy');
    
    Route::get('/purchase', [SupplierController::class, 'purchase'])->name('purchase');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/maintenance', [DashboardController::class, 'maintenance'])->name('dashboard.maintenance');
});