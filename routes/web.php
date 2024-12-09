<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/delete', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/status/update', [\App\Http\Controllers\ProfileController::class, 'updateStatus'])->name('profile.status.update');
    Route::get('/profile/list', [\App\Http\Controllers\ProfileController::class, 'getUserList'])->name('get.users');
    Route::get('/profile/view/{id}', [\App\Http\Controllers\ProfileController::class, 'viewUser'])->name('user.view');
    Route::post('/profile/create', [\App\Http\Controllers\ProfileController::class, 'save'])->name('create.user');
    Route::get('/profile/{id}', [\App\Http\Controllers\ProfileController::class, 'getUserById'])->name('user.get');

    Route::post('/profile/service/save', [\App\Http\Controllers\ProfileController::class, 'profileServiceStore'])->name('profile.service.save');
    Route::post('/profile/wage/save', [\App\Http\Controllers\ProfileController::class, 'profileWageStore'])->name('profile.wage.save');
//-------------------------------------------R-BAC------------------------------------------------------
    Route::resource('permissions',App\Http\Controllers\PermissionController::class);
    Route::resource('roles',App\Http\Controllers\RoleController::class);
    Route::get('/permission/list', [\App\Http\Controllers\PermissionController::class, 'getPermissionList'])->name('permissions.list');
    Route::post('/role/permission', [\App\Http\Controllers\PermissionController::class, 'saveRolePermission'])->name('roles.permission');
    Route::get('/role/list', [\App\Http\Controllers\RoleController::class, 'getRoleList'])->name('roles.list');
    Route::post('/roles/permission/save', [\App\Http\Controllers\RoleController::class, 'save'])->name('roles.save');
    Route::get('/roles/by/id', [\App\Http\Controllers\RoleController::class, 'fetchRoleById'])->name('fetch.role');
    Route::get('/get-permissions-by-role', [\App\Http\Controllers\PermissionController::class, 'getPermissionsByRole'])->name('permissions.by.role');

//    -------------------------------------Lookup values----------------------------------------
    Route::get('/lookup/transaction/list', [\App\Http\Controllers\LookupValueController::class, 'transactionIndex'])->name('lookup.transaction.list');
    Route::get('/lookup/income/list', [\App\Http\Controllers\LookupValueController::class, 'incomeIndex'])->name('lookup.income.list');
    Route::get('/lookup/expense/list', [\App\Http\Controllers\LookupValueController::class, 'expenseIndex'])->name('lookup.expense.list');
    Route::post('/lookup/save', [\App\Http\Controllers\LookupValueController::class, 'save'])->name('lookup.save');

    //lookup/{type} this will iterate lookup/transaction_category,lookup/expense_category,lookup/income_category
    Route::get('/lookup/{type}', [\App\Http\Controllers\LookupValueController::class, 'getLookupList'])->name('get.lookup');
    Route::delete('/lookup/{id}', [\App\Http\Controllers\LookupValueController::class, 'destroy'])->name('lookup.destroy');
    Route::get('/fetch/lookup/{id}', [\App\Http\Controllers\LookupValueController::class, 'getLookupById'])->name('lookup.by.id');

//    ----------------------------------------Companies---------------------------------------------
    Route::get('/company', [\App\Http\Controllers\CompanyController::class, 'index'])->name('company.index');
    Route::get('/company/list', [\App\Http\Controllers\CompanyController::class, 'companyIndex'])->name('company.list');
    Route::get('/company/{id}', [\App\Http\Controllers\CompanyController::class, 'getCompanyById'])->name('fetch.company');

    Route::post('/company/save', [\App\Http\Controllers\CompanyController::class, 'store'])->name('company.save');
    Route::delete('/company/delete', [\App\Http\Controllers\CompanyController::class, 'destroy'])->name('company.destroy');
    Route::post('/company/update', [\App\Http\Controllers\CompanyController::class, 'updateStatus'])->name('company.status.update');

    //    ----------------------------------------Stations---------------------------------------------
    Route::get('/station', [\App\Http\Controllers\StationController::class, 'index'])->name('station.index');
    Route::get('/station/list', [\App\Http\Controllers\StationController::class, 'stationIndex'])->name('station.list');
    Route::get('/station/{id}', [\App\Http\Controllers\StationController::class, 'getStationById'])->name('fetch.station');
    Route::post('/get-managers', [\App\Http\Controllers\StationController::class, 'getManagers'])->name('get.managers');

    Route::post('/station/save', [\App\Http\Controllers\StationController::class, 'store'])->name('station.save');
    Route::delete('/station/delete', [\App\Http\Controllers\StationController::class, 'destroy'])->name('station.destroy');
    Route::post('/station/update', [\App\Http\Controllers\StationController::class, 'updateStatus'])->name('station.status.update');

    //    ----------------------------------------categories---------------------------------------------
    Route::get('/category', [\App\Http\Controllers\CategoryController::class, 'index'])->name('category.index');
    Route::get('/category/list', [\App\Http\Controllers\CategoryController::class, 'categoryIndex'])->name('category.list');
    Route::get('/category/{id}', [\App\Http\Controllers\CategoryController::class, 'getCategoryById'])->name('fetch.category');

    Route::post('/category/save', [\App\Http\Controllers\CategoryController::class, 'store'])->name('category.save');
    Route::delete('/category/{id}', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('category.destroy');
    //    ---------------------Configuration--------------------------------------------------------------
    Route::get('/configuration', [\App\Http\Controllers\ConfigurationController::class, 'index'])->name('configuration.index');
    Route::get('/configuration/list', [\App\Http\Controllers\ConfigurationController::class, 'ConfigIndex'])->name('configuration.list');
    Route::get('/configuration/{id}', [\App\Http\Controllers\ConfigurationController::class, 'getConfigById'])->name('fetch.configuration');

    Route::post('/configuration/save', [\App\Http\Controllers\ConfigurationController::class, 'store'])->name('configuration.save');
    Route::post('/configuration/delete', [\App\Http\Controllers\ConfigurationController::class, 'destroy'])->name('configuration.destroy');
    //    ----------------------------------------Services---------------------------------------------
    Route::get('/service', [\App\Http\Controllers\ServiceController::class, 'index'])->name('service.index');
    Route::get('/service/list', [\App\Http\Controllers\ServiceController::class, 'serviceIndex'])->name('service.list');
    Route::get('/service/{id}', [\App\Http\Controllers\ServiceController::class, 'getServiceById'])->name('fetch.service');

    Route::post('/service/save', [\App\Http\Controllers\ServiceController::class, 'store'])->name('service.save');
    Route::delete('/service/delete', [\App\Http\Controllers\ServiceController::class, 'destroy'])->name('service.destroy');
    Route::post('/service/update', [\App\Http\Controllers\ServiceController::class, 'updateStatus'])->name('service.status.update');

    //    ----------------------------------------Stations Services---------------------------------------------
    Route::get('/station/service/{station_id}', [\App\Http\Controllers\StationController::class, 'stationServiceIndex'])->name('station.service.index');
    Route::post('/station/service/save', [\App\Http\Controllers\StationController::class, 'stationServiceStore'])->name('station.service.save');

    //===========================================Income===========================================================

    Route::get('/income', [\App\Http\Controllers\IncomeController::class, 'index'])->name('income.index');
    Route::get('/income/list', [\App\Http\Controllers\IncomeController::class, 'incomeIndex'])->name('income.list');
    Route::get('/income/{id}', [\App\Http\Controllers\IncomeController::class, 'getIncomeById'])->name('fetch.income');

    Route::post('/income/save', [\App\Http\Controllers\IncomeController::class, 'store'])->name('income.save');
    Route::delete('/income/delete', [\App\Http\Controllers\IncomeController::class, 'destroy'])->name('income.destroy');
    Route::get('/income/view/{id}', [\App\Http\Controllers\IncomeController::class, 'show'])->name('income.view');
    Route::post('/income/update', [\App\Http\Controllers\IncomeController::class, 'updateStatus'])->name('income.status.update');

    //===========================================Income===========================================================

    Route::get('/expense', [\App\Http\Controllers\ExpenseController::class, 'index'])->name('expense.index');
    Route::get('/expense/list', [\App\Http\Controllers\ExpenseController::class, 'expenseIndex'])->name('expense.list');
    Route::get('/expense/{id}', [\App\Http\Controllers\ExpenseController::class, 'getExpenseById'])->name('fetch.expense');

    Route::post('/expense/save', [\App\Http\Controllers\ExpenseController::class, 'store'])->name('expense.save');
    Route::delete('/expense/delete', [\App\Http\Controllers\ExpenseController::class, 'destroy'])->name('expense.destroy');
    Route::get('/expense/view/{id}', [\App\Http\Controllers\ExpenseController::class, 'show'])->name('expense.view');
    Route::post('/expense/update', [\App\Http\Controllers\ExpenseController::class, 'updateStatus'])->name('expense.status.update');
});


require __DIR__.'/auth.php';


