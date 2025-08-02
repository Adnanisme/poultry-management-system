<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PoultryRecordController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CollectorController;
use App\Http\Controllers\Admin\OtherSaleController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\ReportController as StaffReportController;
use App\Http\Controllers\Staff\PoultryRecordController as StaffPoultryRecordController;
use App\Http\Controllers\Staff\SaleController as StaffSaleController;
use App\Http\Controllers\Staff\ExpenseController as StaffExpenseController;
use App\Http\Controllers\Staff\OtherSaleController as StaffOtherSaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif (auth()->check()) {
        return redirect()->route('staff.dashboard');
    } else {
        return redirect()->route('login');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    
    // Poultry Records
    Route::resource('poultry-records', PoultryRecordController::class);
    Route::get('/poultry-records/reports/daily', [PoultryRecordController::class, 'dailyReport'])->name('poultry-records.daily-report');
    
    // Collectors
    Route::resource('collectors', CollectorController::class);
    
    // Sales
    Route::resource('sales', SaleController::class);
    Route::get('/sales/reports/daily', [SaleController::class, 'dailyReport'])->name('sales.daily-report');
    Route::get('/sales/reports/monthly', [SaleController::class, 'monthlyReport'])->name('sales.monthly-report');
    Route::get('/sales/reports/daily/pdf', [SaleController::class, 'dailyReportPdf'])->name('sales.daily-report.pdf');
    Route::get('/sales/reports/monthly/pdf', [SaleController::class, 'monthlyReportPdf'])->name('sales.monthly-report.pdf');
    
    // Expenses
    Route::resource('expenses', ExpenseController::class);
    Route::get('/expenses/reports/daily', [ExpenseController::class, 'dailyReport'])->name('expenses.daily-report');
    Route::get('/expenses/reports/monthly', [ExpenseController::class, 'monthlyReport'])->name('expenses.monthly-report');
    Route::get('/expenses/reports/daily/pdf', [ExpenseController::class, 'dailyReportPdf'])->name('expenses.daily-report.pdf');
    Route::get('/expenses/reports/monthly/pdf', [ExpenseController::class, 'monthlyReportPdf'])->name('expenses.monthly-report.pdf');
    
    // Other Sales
    Route::resource('other-sales', OtherSaleController::class);
    Route::get('/other-sales/reports/daily', [OtherSaleController::class, 'dailyReport'])->name('other-sales.daily-report');
    Route::get('/other-sales/reports/daily/pdf', [OtherSaleController::class, 'dailyReportPdf'])->name('other-sales.daily-report.pdf');
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

// Staff Routes (includes admin access)
Route::middleware(['auth', 'staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', [StaffDashboardController::class, 'storeDailyRecord'])->name('dashboard.store');
    
    // Poultry Records (view and create only)
    Route::get('/poultry-records', [StaffPoultryRecordController::class, 'index'])->name('poultry-records.index');
    Route::get('/poultry-records/create', [StaffPoultryRecordController::class, 'create'])->name('poultry-records.create');
    Route::post('/poultry-records', [StaffPoultryRecordController::class, 'store'])->name('poultry-records.store');
    Route::get('/poultry-records/{poultryRecord}', [StaffPoultryRecordController::class, 'show'])->name('poultry-records.show');
    
    // Sales (view and create only)
    Route::get('/sales', [StaffSaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [StaffSaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [StaffSaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [StaffSaleController::class, 'show'])->name('sales.show');
    
    // Expenses (view and create only)
    Route::get('/expenses', [StaffExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/create', [StaffExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [StaffExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/{expense}', [StaffExpenseController::class, 'show'])->name('expenses.show');

    // Other Sales (view and create only)
    Route::get('/other-sales', [StaffOtherSaleController::class, 'index'])->name('other-sales.index');
    Route::get('/other-sales/create', [StaffOtherSaleController::class, 'create'])->name('other-sales.create');
    Route::post('/other-sales', [StaffOtherSaleController::class, 'store'])->name('other-sales.store');
    Route::get('/other-sales/{otherSale}', [StaffOtherSaleController::class, 'show'])->name('other-sales.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
