<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\StockController;
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
    Route::get('/project/{id}', [ProjectController::class, 'show'])->name('project.show');
    // ? Order Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
    Route::put('/order/{id}', [OrderController::class, 'update'])->name('order.update');
    Route::delete('/order/{id}', [OrderController::class, 'destroy'])->name('order.destroy');
    Route::get('/order/{id}', [OrderController::class, 'edit'])->name('order.edit');
    Route::get('/order/show/{id}', [OrderController::class, 'show'])->name('order.show');
    Route::post('/order/status/{id}', [OrderController::class, 'changeStatus'])->name('order.changeStatus');
    // Print Label
    Route::get('/order/print/{id}', [OrderController::class, 'print'])->name('order.print');
    //  ?  Products
    Route::get('/products', [ProductController::class, 'index'])->name('product.index');
    Route::get('/get-products', [ProductController::class, 'getProducts'])->name('products.index');
    Route::get('/search-products', [ProductController::class, 'search'])->name('products.search');
    Route::post('/products/store', [ProductController::class, 'productStore'])->name('product.store');
    Route::put('/products/{id}', [ProductController::class, 'productUpdate'])->name('product.update');
    Route::delete('/products/{id}', [ProductController::class, 'productDestroy'])->name('product.destroy');

    // ? Payment Routes
    Route::post('/payments', [PaymentController::class, 'store'])->name('payment.store');
    // ? Stock
    Route::get('/stock', [StockController::class, 'index'])->name('stock');
    Route::get('/purchase', [SupplierController::class, 'purchase'])->name('purchase');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/maintenance', [DashboardController::class, 'maintenance'])->name('dashboard.maintenance');

    // ? Expenses
    // Transportation Expenses
    Route::get('/expenses/variable', [ExpenseController::class, 'variable'])->name('expenses.variable');
    Route::post('/expenses/transportation-expenses', [ExpenseController::class, 'transportationStore'])->name('transportation-expenses.store');
    Route::get('/expenses/transportation', [ExpenseController::class, 'transportation'])->name('expenses.transportation');
    // ? Employee
    Route::get('/employee', [EmployerController::class, 'index'])->name('employee.index');
    Route::post('/employee/store', [EmployerController::class, 'store'])->name('employee.store');
    Route::put('/employee/{id}', [EmployerController::class, 'update'])->name('employee.update');
    Route::delete('/employee/{id}', [EmployerController::class, 'destroy'])->name('employee.destroy');
    // Profession
    Route::get('/profession', [EmployerController::class, 'profession'])->name('profession');
    Route::post('/profession/store', [EmployerController::class, 'storeProfession'])->name('profession.store');
    Route::put('/profession/{id}', [EmployerController::class, 'updateProfession'])->name('profession.update');
    Route::delete('/profession/{id}', [EmployerController::class, 'deleteProfession'])->name('profession.destroy');
    // ? Shift
    Route::get('/shift', [ShiftController::class, 'index'])->name('shift.index');
    Route::get('/shift/generate', [ShiftController::class, 'index'])->name('shift.generate');
    Route::post('/shifts/generate-weekly', [ShiftController::class, 'generateWeeklyShifts'])->name('shifts.generateWeekly');
    // Assign employees to shift
    Route::post('/shifts/assign-users', [ShiftController::class, 'assignUsers'])->name('shift.assignUsers');
    // Get employees for a shift
    Route::get('/shifts/{shift}/employees', [ShiftController::class, 'getEmployees'])->name('shifts.getEmployees');
    // Attendance
    Route::get('/shift/attendance', [ShiftController::class, 'attendance'])->name('shift.attendance');
    // Mark attendance
    Route::post('/shift/mark-attendance', [ShiftController::class, 'markAttendance'])->name('shift.mark-attendance');

});