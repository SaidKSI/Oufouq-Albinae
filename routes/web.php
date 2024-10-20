<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaskController;
use App\Models\Delivery;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

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
    // project estimate invoice
    // Route::get('/projects/{id}/invoice', [ProjectController::class, 'estimateInvoice'])->name('estimate.invoice');
    Route::get('/projects/payment/{id}/invoice', [ProjectController::class, 'paymentEstimateInvoice'])->name('estimate.payment.invoice');
    Route::get('/projects/{id}/invoice', [ProjectController::class, 'showInvoice'])->name('project-estimate.invoice');
    // * Delivery invoice
    Route::get('/projects/delivery/create', [DeliveryController::class, 'deliveryInvoice'])->name('delivery.invoice');
    Route::get('/order/delivery', [DeliveryController::class, 'index'])->name('delivery');
    Route::post('/order/delivery/store', [DeliveryController::class, 'store'])->name('delivery.store');
    Route::get('/order/delivery/{id}', [DeliveryController::class, 'show'])->name('delivery.show');
    Route::delete('/order/delivery/delete/{id}', [DeliveryController::class, 'destroy'])->name('delivery.destroy');
    Route::get('/order/delivery/print/{id}', [DeliveryController::class, 'print'])->name('delivery.print');
    Route::get('/order/delivery/{number}/to-number', [DeliveryController::class, 'numberToFrenchWords'])->name('numberToWords');
    // ? delivery bills
    Route::post('/delivery/{id}/add-bill', [DeliveryController::class, 'addBill'])->name('delivery.add-bill');
    // ? Project Estimate
    Route::get('/projects/estimate', [ProjectController::class, 'estimate'])->name('estimates');
    Route::post('/projects/estimate/store', [ProjectController::class, 'storeEstimate'])->name('estimate.store');
    Route::put('/projects/estimate/{id}', [ProjectController::class, 'updateEstimate'])->name('estimate.update');
    Route::delete('/projects/estimate/delete/{id}', [ProjectController::class, 'destroyEstimate'])->name('estimate.destroy');
    // get client projects
    Route::get('/client/{client}/projects', [ClientController::class, 'getClientProjects'])->name('client.projects');
    // * Estimate Payment 
    Route::get('projects/estimate/payment', [PaymentController::class, 'estimatePayment'])->name('estimate.payment');
    Route::post('projects/estimate/payment/store', [PaymentController::class, 'storeEstimatePayment'])->name('estimatePayment.store');
    Route::get('/estimates/{id}', [EstimateController::class, 'show'])->name('estimate.show');
    Route::get('/invoices/{id}', [EstimateController::class, 'show'])->name('invoice.show');
    Route::get('/project-estimates/create-invoice', [EstimateController::class, 'createInvoice'])->name('project-estimate.create-invoice');
    Route::post('/project-estimates/store-invoice', [EstimateController::class, 'storeInvoice'])->name('project-estimate.store-invoice');
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
    // ? Invoice
    Route::get('facture/invoices', [ProjectController::class, 'createInvoice'])->name('invoice.create');
    Route::post('facture/invoices/store', [ProjectController::class, 'storeInvoice'])->name('project.store_invoice');
    // ? Payment Routes
    Route::post('/payments', [PaymentController::class, 'store'])->name('payment.store');
    // ? Stock
    Route::get('/stock', [StockController::class, 'index'])->name('stock');
    Route::get('/purchase', [SupplierController::class, 'purchase'])->name('purchase');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/maintenance', [DashboardController::class, 'maintenance'])->name('dashboard.maintenance');

    // ? Expenses
    // Transportation Expenses
    Route::get('/expenses/index', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expenses/store', [ExpenseController::class, 'store'])->name('expense.store');
    Route::put('/expenses/{id}', [ExpenseController::class, 'update'])->name('expense.update');
    Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy'])->name('expense.destroy');

    Route::post('/expenses/transportation-expenses', [ExpenseController::class, 'transportationStore'])->name('transportation-expenses.store');
    Route::get('/expenses/transportation', [ExpenseController::class, 'transportation'])->name('expenses.transportation');
    // ? Employee
    Route::get('/employee', [EmployerController::class, 'index'])->name('employee.index');
    Route::post('/employee/store', [EmployerController::class, 'store'])->name('employee.store');
    Route::put('/employee/{id}', [EmployerController::class, 'update'])->name('employee.update');
    Route::delete('/employee/{id}', [EmployerController::class, 'destroy'])->name('employee.destroy');
    // Employee Payments
    Route::get('/employee/payments', [PaymentController::class, 'payment'])->name('employee.payment');
    Route::post('/employee/add-payment', [PaymentController::class, 'employer_payment'])->name('employee.storePayment');
    // Get the Employer total remaining wage 
    Route::get('/employees/{employee}/total-wage', [PaymentController::class, 'getTotalWage']);
    // Employer Payment invoices
    Route::get('/employee/invoice/{id}', [PaymentController::class, 'invoice'])->name('employee.invoice');
    // Profession
    Route::get('/profession', [EmployerController::class, 'profession'])->name('profession');
    Route::post('/profession/store', [EmployerController::class, 'storeProfession'])->name('profession.store');
    Route::put('/profession/{id}', [EmployerController::class, 'updateProfession'])->name('profession.update');
    Route::delete('/profession/{id}', [EmployerController::class, 'deleteProfession'])->name('profession.destroy');
    // ? Shift
    Route::get('/shift', [ShiftController::class, 'index'])->name('shift.index');
    Route::get('/shift/generate', [ShiftController::class, 'index'])->name('shift.generate');
    Route::post('/shifts/generate-weekly', [ShiftController::class, 'generateWeeklyShifts'])->name('shifts.generateWeekly');
    Route::get('/shifts/overview', [ShiftController::class, 'show'])->name('shift.overview');
    Route::get('/shifts/previous-week', [ShiftController::class, 'previousWeek'])->name('shifts.previousWeek');
    Route::get('/shifts/next-week', [ShiftController::class, 'nextWeek'])->name('shifts.nextWeek');
    // Assign employees to shift
    Route::post('/shifts/assign-users', [ShiftController::class, 'assignUsers'])->name('shift.assignUsers');
    // Get employees for a shift
    Route::get('/shifts/{shift}/employees', [ShiftController::class, 'getEmployees'])->name('shifts.getEmployees');
    // Attendance
    Route::get('/shift/attendance', [ShiftController::class, 'attendance'])->name('shift.attendance');
    // Mark attendance
    Route::post('/shift/mark-attendance', [ShiftController::class, 'markAttendance'])->name('shift.mark-attendance');
    // ? Task
    Route::get('/tasks', [TaskController::class, 'index'])->name('task.index');
    Route::post('/tasks/store', [TaskController::class, 'store'])->name('task.store');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('task.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('task.destroy');
    Route::post('/tasks/{id}/update-status', [TaskController::class, 'updateStatus']);
    // ? Settings
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
    // Capital Transactions
    Route::post('/capital/transactions', [DashboardController::class, 'storeTransaction'])->name('capital.transactions.store');
    Route::get('/company/capital', [DashboardController::class, 'getCapital'])->name('company.capital');
    // capital history over time line chart
    Route::get('/company/capital-history', [DashboardController::class, 'getCapitalHistory'])->name('company.capital.history');

    Route::get('/invoice/{invoice}', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/invoice/{invoice}/print', [InvoiceController::class, 'print'])->name('invoice.print');
});