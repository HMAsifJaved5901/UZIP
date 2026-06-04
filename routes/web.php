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
    Route::delete('/profile/status/update', [\App\Http\Controllers\ProfileController::class, 'changeStatus'])->name('profile.status.update');
    Route::get('/profile/list', [\App\Http\Controllers\ProfileController::class, 'dataTableList'])->name('get.users');
    Route::get('/profile/view/{id}', [\App\Http\Controllers\ProfileController::class, 'show'])->name('user.view');
    Route::post('/profile/create', [\App\Http\Controllers\ProfileController::class, 'save'])->name('create.user');
    Route::get('/profile/{id}', [\App\Http\Controllers\ProfileController::class, 'getById'])->name('user.get');
    Route::get('/profile/payroll-type-by-roll/{code}', [\App\Http\Controllers\ProfileController::class, 'PayRollTypeByRoll'])->name('payroll.type.by.roll');
    Route::post('/employee-services/store', [\App\Http\Controllers\ProfileController::class, 'employeeServiceStore'])->name('profile.service.save');
    Route::post('/employee-shift/store', [\App\Http\Controllers\ProfileController::class, 'employeeShiftStore'])->name('profile.shift.save');
    Route::post('/employee-services/get-services', [\App\Http\Controllers\ProfileController::class, 'getServiceList']);
    Route::get('/profile/get-methods/{id}', [\App\Http\Controllers\ProfileController::class, 'getPayrollMethods']);
    Route::post('/profile/profile.save-payroll', [\App\Http\Controllers\ProfileController::class, 'savePayroll'])->name('profile.save-payroll');



//-------------------------------------------R-BAC------------------------------------------------------
    Route::resource('permissions',App\Http\Controllers\PermissionController::class);
    Route::resource('roles',App\Http\Controllers\RoleController::class);

    Route::get('/permission/list', [\App\Http\Controllers\PermissionController::class, 'getPermissionList'])->name('permissions.list');
    Route::post('/role/permission', [\App\Http\Controllers\PermissionController::class, 'saveRolePermission'])->name('roles.permission');
    Route::get('/role/list', [\App\Http\Controllers\RoleController::class, 'getList'])->name('roles.list');
    Route::post('/roles/permission/save', [\App\Http\Controllers\RoleController::class, 'save'])->name('roles.save');
    Route::get('/roles/by/id', [\App\Http\Controllers\RoleController::class, 'getById'])->name('fetch.role');
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

//    --------------------------------------------supplier/----------------------------------------
    Route::get('/supplier', [\App\Http\Controllers\SupplierController::class, 'Index'])->name('supplier.index');
    Route::get('/supplier/list', [\App\Http\Controllers\SupplierController::class, 'dataTableList'])->name('supplier.list');
    Route::get('/supplier/{id}', [\App\Http\Controllers\SupplierController::class, 'getById'])->name('fetch.supplier');
    Route::post('/supplier/save', [\App\Http\Controllers\SupplierController::class, 'store'])->name('supplier.save');
    Route::delete('/supplier/delete', [\App\Http\Controllers\SupplierController::class, 'destroy'])->name('supplier.destroy');
    Route::post('/supplier/update', [\App\Http\Controllers\SupplierController::class, 'changeStatus'])->name('supplier.status.update');
    Route::get('/supplier/view/{id}', [\App\Http\Controllers\SupplierController::class, 'SupplierView'])->name('web.supplier.view');
    //    --------------------------------------------business/----------------------------------------
    Route::get('/business', [\App\Http\Controllers\BusinessController::class, 'Index'])->name('business.index');
    Route::get('/business/list', [\App\Http\Controllers\BusinessController::class, 'dataTableList'])->name('business.list');
    Route::get('/business/{id}', [\App\Http\Controllers\BusinessController::class, 'getById'])->name('parent.fetch.company');
    Route::post('/business/save', [\App\Http\Controllers\BusinessController::class, 'store'])->name('business.save');
    Route::delete('/business/delete', [\App\Http\Controllers\BusinessController::class, 'destroy'])->name('business.destroy');
    Route::post('/business/update', [\App\Http\Controllers\BusinessController::class, 'changeStatus'])->name('business.status.update');

//-----------------------------------------------------Companies------------------------------------------------------------------------
    Route::get('/company', [\App\Http\Controllers\CompanyController::class, 'index'])->name('company.index');
    Route::get('/company/list', [\App\Http\Controllers\CompanyController::class, 'dataTableList'])->name('company.list');
    Route::get('/company/{id}', [\App\Http\Controllers\CompanyController::class, 'getById'])->name('fetch.company');
    Route::post('/company/save', [\App\Http\Controllers\CompanyController::class, 'store'])->name('company.save');
    Route::delete('/company/delete', [\App\Http\Controllers\CompanyController::class, 'destroy'])->name('company.destroy');
    Route::post('/company/update', [\App\Http\Controllers\CompanyController::class, 'changeStatus'])->name('company.status.update');

    //    ----------------------------------------Stations---------------------------------------------
    Route::get('/station', [\App\Http\Controllers\StationController::class, 'index'])->name('station.index');
    Route::get('/station/list', [\App\Http\Controllers\StationController::class, 'dataTableList'])->name('station.list');
    Route::get('/station/{id}', [\App\Http\Controllers\StationController::class, 'getById'])->name('fetch.station');
    Route::post('/get-managers', [\App\Http\Controllers\StationController::class, 'getManagerList'])->name('get.managers');

    Route::post('/station/save', [\App\Http\Controllers\StationController::class, 'store'])->name('station.save');
    Route::delete('/station/delete', [\App\Http\Controllers\StationController::class, 'destroy'])->name('station.destroy');
    Route::post('/station/update', [\App\Http\Controllers\StationController::class, 'changeStatus'])->name('station.status.update');
    Route::post('/station/service/remove', [\App\Http\Controllers\StationController::class, 'stationServiceRemove'])->name('station.service.remove');

    Route::post('/supplier/add-update', [\App\Http\Controllers\StationController::class, 'SupplierAndCommissionSave'])->name('supplier.add.update');
    Route::post('/fuel-commission/add-update', [\App\Http\Controllers\StationController::class, 'FuelCommissionAddUpdate'])->name('fuel.commission.add.update');
    Route::post('/station-pos/add-update', [\App\Http\Controllers\StationController::class, 'StationPosMachineAddUpdate'])->name('station.pos.add.update');
    Route::post('/station-lotto/add-update', [\App\Http\Controllers\StationController::class, 'StationLottoMachineAddUpdate'])->name('station.lotto.add.update');
    Route::post('/station-restaurant-pos/add-update', [\App\Http\Controllers\StationController::class, 'StationRestaurantMachineAddUpdate'])->name('station.restaurant.pos.add.update');
    Route::post('/station-car-wash/add-update', [\App\Http\Controllers\StationController::class, 'carWashAddUpdate'])->name('station.car.wash.operator.add.update');

    Route::get('/station/service/get-supplier-and-fuel-commission', [\App\Http\Controllers\StationController::class, 'getSupplierWithFuelCommission'])->name('service.getSupplierAndFuelCommission');
    Route::get('/station/service/get-supplier', [\App\Http\Controllers\StationController::class, 'getSupplierList'])->name('station.service.get.supplier');
    Route::get('/station/service/get-fuel-commission', [\App\Http\Controllers\StationController::class, 'getFuelCommission'])->name('service.getFuelCommission');
    Route::get('/station/service/pos-machine', [\App\Http\Controllers\StationController::class, 'getPosMachineList'])->name('station.service.getPos.machine');
    Route::get('/station/service/restaurant-pos-machine', [\App\Http\Controllers\StationController::class, 'getRestaurantPosMachineList'])->name('service.get.restaurant.pos.machine');
    Route::get('/station/service/lotto-machine', [\App\Http\Controllers\StationController::class, 'getLottoMachineList'])->name('service.get.lotto.machine');
    Route::get('/station/service/car-wash/operator', [\App\Http\Controllers\StationController::class, 'getCarWashOperatorList'])->name('station.service.get.carWash.Operator');


    //    ----------------------------------------categories---------------------------------------------
    Route::get('/category', [\App\Http\Controllers\CategoryController::class, 'index'])->name('category.index');
    Route::get('/category/list', [\App\Http\Controllers\CategoryController::class, 'dataTableList'])->name('category.list');
    Route::get('/category/{id}', [\App\Http\Controllers\CategoryController::class, 'getById'])->name('fetch.category');

    Route::post('/category/save', [\App\Http\Controllers\CategoryController::class, 'store'])->name('category.save');
    Route::delete('/category/{id}', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('category.destroy');
    //    ---------------------Configuration--------------------------------------------------------------
    Route::get('/configuration/{type}', [\App\Http\Controllers\ConfigurationController::class, 'index'])->name('configuration.index');
    Route::get('/configuration/list/{type}', [\App\Http\Controllers\ConfigurationController::class, 'dataTableList'])->name('configuration.list');
    Route::get('/configuration/fetch/{id}', [\App\Http\Controllers\ConfigurationController::class, 'getById'])->name('fetch.configuration');
    Route::post('/configuration/save', [\App\Http\Controllers\ConfigurationController::class, 'store'])->name('configuration.save');
    Route::post('/configuration/delete', [\App\Http\Controllers\ConfigurationController::class, 'destroy'])->name('configuration.destroy');
    //    ----------------------------------------Services---------------------------------------------
    Route::get('/service', [\App\Http\Controllers\ServiceController::class, 'index'])->name('service.index');
    Route::get('/service/list', [\App\Http\Controllers\ServiceController::class, 'dataTableList'])->name('service.list');
    Route::get('/service/{id}', [\App\Http\Controllers\ServiceController::class, 'getById'])->name('fetch.service');
    Route::post('/service/save', [\App\Http\Controllers\ServiceController::class, 'store'])->name('service.save');
    Route::delete('/service/delete', [\App\Http\Controllers\ServiceController::class, 'destroy'])->name('service.destroy');
    Route::post('/service/update', [\App\Http\Controllers\ServiceController::class, 'changeStatus'])->name('service.status.update');

    //    ----------------------------------------Stations Services---------------------------------------------
    Route::get('/station/service/{station_id}', [\App\Http\Controllers\StationController::class, 'stationServiceIndex'])->name('station.service.index');
    Route::post('/station/service/save', [\App\Http\Controllers\StationController::class, 'stationServiceStore'])->name('station.service.save');

    //===========================================Income===========================================================

    Route::get('/income', [\App\Http\Controllers\IncomeController::class, 'index'])->name('income.index');
    Route::get('/income/list', [\App\Http\Controllers\IncomeController::class, 'dataTableList'])->name('income.list');
    Route::get('/income/{id}', [\App\Http\Controllers\IncomeController::class, 'getById'])->name('fetch.income');

    Route::post('/income/save', [\App\Http\Controllers\IncomeController::class, 'store'])->name('income.save');
    Route::delete('/income/delete', [\App\Http\Controllers\IncomeController::class, 'destroy'])->name('income.destroy');
    Route::get('/income/view/{id}', [\App\Http\Controllers\IncomeController::class, 'show'])->name('income.view');
//    Route::post('/income/update', [\App\Http\Controllers\IncomeController::class, 'update'])->name('income.status.update');

    Route::get('/get-services/{id}', [\App\Http\Controllers\IncomeController::class, 'getServicesList']);
    Route::get('/get-categories/{category}/{id}', [\App\Http\Controllers\IncomeController::class, 'getCategoryList']);

    Route::post('/income/sales/{id}/approve', [\App\Http\Controllers\IncomeController::class, 'approve'])->name('cstore.sale.approve');
    Route::post('/income/sales/{id}/reject', [\App\Http\Controllers\IncomeController::class, 'reject'])->name('cstore.sale.reject');
    Route::post('/income/sales/{id}/pending', [\App\Http\Controllers\IncomeController::class, 'pending'])->name('cstore.sale.pending');

    //===========================================Expense===========================================================

    Route::get('/expense', [\App\Http\Controllers\ExpenseController::class, 'index'])->name('expense.index');
    Route::get('/expense/list', [\App\Http\Controllers\ExpenseController::class, 'dataTableList'])->name('expense.list');
    Route::get('/expense/{id}', [\App\Http\Controllers\ExpenseController::class, 'getById'])->name('fetch.expense');

    Route::post('/expense/save', [\App\Http\Controllers\ExpenseController::class, 'store'])->name('expense.save');
    Route::delete('/expense/delete', [\App\Http\Controllers\ExpenseController::class, 'destroy'])->name('expense.destroy');
    Route::get('/expense/view/{id}', [\App\Http\Controllers\ExpenseController::class, 'show'])->name('expense.view');
//    Route::post('/expense/update', [\App\Http\Controllers\ExpenseController::class, 'updateStatus'])->name('expense.status.update');
    Route::post('/station/expense/{id}/approve', [\App\Http\Controllers\ExpenseController::class, 'approve'])->name('expense.operation.approve');
    Route::post('/station/expense/{id}/reject', [\App\Http\Controllers\ExpenseController::class, 'reject'])->name('expense.operation.reject');
    Route::post('/station/expense/{id}/pending', [\App\Http\Controllers\ExpenseController::class, 'pending'])->name('expense.operation.pending');

    //===========================================Deposits===========================================================

    Route::get('/deposit', [\App\Http\Controllers\DepositController::class, 'index'])->name('deposit.index');
    Route::get('/deposit/list', [\App\Http\Controllers\DepositController::class, 'dataTableList'])->name('deposit.list');
    Route::get('/deposit/{id}', [\App\Http\Controllers\DepositController::class, 'getById'])->name('fetch.deposit');
    Route::post('/deposit/save', [\App\Http\Controllers\DepositController::class, 'store'])->name('deposit.save');
    Route::delete('/deposit/delete', [\App\Http\Controllers\DepositController::class, 'destroy'])->name('deposit.destroy');
    Route::get('/deposit/view/{id}', [\App\Http\Controllers\DepositController::class, 'show'])->name('deposit.view');
    Route::get('/deposit/status/update/{id}/{status}', [\App\Http\Controllers\DepositController::class, 'changeStatus'])->name('deposit.status.update');
    Route::post('/station/deposit/{id}/approve', [\App\Http\Controllers\DepositController::class, 'approve'])->name('deposit.operation.approve');
    Route::post('/station/deposit/{id}/reject', [\App\Http\Controllers\DepositController::class, 'reject'])->name('deposit.operation.reject');
    Route::post('/station/deposit/{id}/pending', [\App\Http\Controllers\DepositController::class, 'pending'])->name('deposit.operation.pending');

    //===========================================Sale DC Mart & Aloha===========================================================

    Route::get('/web/sales/restaurant/dcmart/index', [\App\Http\Controllers\RestaurantSaleController::class, 'indexDcMart'])->name('web.sales.restaurant.dcmart.index');
    Route::get('/web/sales/restaurant/dcmart/list', [\App\Http\Controllers\RestaurantSaleController::class, 'dcMartDataTableList'])->name('web.sales.restaurant.dcmart.list');
    Route::get('/web/sales/restaurant/dcmart/view/{id}', [\App\Http\Controllers\RestaurantSaleController::class, 'showDcMart'])->name('web.sales.restaurant.dcmart.view');
    Route::delete('/web/sales/restaurant/dcmart/delete', [\App\Http\Controllers\RestaurantSaleController::class, 'destroyDcMart'])->name('web.sales.restaurant.dcmart.destroy');
    Route::post('/station/dcMart/sales/{id}/approve', [\App\Http\Controllers\RestaurantSaleController::class, 'DcMartSaleApprove'])->name('dcMart.sale.approve');
    Route::post('/station/dcMart/sales/{id}/reject', [\App\Http\Controllers\RestaurantSaleController::class, 'DcMartSaleReject'])->name('dcMart.sale.reject');


    Route::get('/web/sales/restaurant/aloha/index', [\App\Http\Controllers\RestaurantSaleController::class, 'indexAloha'])->name('web.sales.restaurant.aloha.index');
    Route::get('/web/sales/restaurant/aloha/list', [\App\Http\Controllers\RestaurantSaleController::class, 'alohaDataTableList'])->name('web.sales.restaurant.aloha.list');
    Route::get('/web/sales/restaurant/aloha/view/{id}', [\App\Http\Controllers\RestaurantSaleController::class, 'showAloha'])->name('web.sales.restaurant.aloha.view');
    Route::delete('/web/sales/restaurant/aloha/delete', [\App\Http\Controllers\RestaurantSaleController::class, 'destroyAloha'])->name('web.sales.restaurant.aloha.destroy');
    Route::post('/station/aloha/sales/{id}/approve', [\App\Http\Controllers\RestaurantSaleController::class, 'AlohaSaleApprove'])->name('aloha.sale.approve');
    Route::post('/station/aloha/sales/{id}/reject', [\App\Http\Controllers\RestaurantSaleController::class, 'AlohaSaleReject'])->name('aloha.sale.reject');

    //===========================================Attendance===========================================================

    Route::get('/employee/attendance', [\App\Http\Controllers\EmployeeAttendanceController::class, 'index'])->name('employee.attendance.index');
    Route::get('/employee/attendance/list', [\App\Http\Controllers\EmployeeAttendanceController::class, 'attendanceIndex'])->name('employee.attendance.list');
    Route::post('/employee/attendance/save', [\App\Http\Controllers\EmployeeAttendanceController::class, 'uploadAttendance'])->name('employee.attendance.save');
    Route::get('/download-sample-attendance', [\App\Http\Controllers\EmployeeAttendanceController::class, 'downloadSampleAttendance'])->name('attendance.downloadSample');

    //===========================================Payroll===========================================================

    Route::get('/payroll/calculate', [\App\Http\Controllers\EmployeeWageController::class, 'calculateWageIndex'])->name('wages.calculate.index');
    Route::delete('/payroll/wage/delete', [\App\Http\Controllers\EmployeeWageController::class, 'deleteWage'])->name('delete.wage');
    Route::post('/payroll/wage/approve', [\App\Http\Controllers\EmployeeWageController::class, 'approveWages'])->name('approve.wage');
    Route::get('/payroll/pending', [\App\Http\Controllers\EmployeeWageController::class, 'pendingWageIndex'])->name('wages.pending.index');
    Route::get('/payroll/paid', [\App\Http\Controllers\EmployeeWageController::class, 'paidWageIndex'])->name('wages.paid.index');

    Route::post('/calculate/wage', [\App\Http\Controllers\EmployeeWageController::class, 'calculateWage'])->name('calculate.wage');
    Route::get('/pending/wage/list', [\App\Http\Controllers\EmployeeWageController::class, 'pendingWageListing'])->name('pending.wage.list');
    Route::get('/paid/wage/list', [\App\Http\Controllers\EmployeeWageController::class, 'paidWageListing'])->name('paid.wage.list');

    //===========================================CIH===========================================================

    Route::get('/web/station/cih/index', [\App\Http\Controllers\CihController::class, 'index'])->name('web.station.cih.index');
    Route::get('/web/station/cih/list', [\App\Http\Controllers\CihController::class, 'dataTableList'])->name('web.station.cih.list');
    Route::get('/web/station/cih/view/{id}', [\App\Http\Controllers\CihController::class, 'show'])->name('web.station.cih.view');
    Route::get('/web/station/cih/register/view/{id}', [\App\Http\Controllers\CihController::class, 'viewRegister'])->name('web.station.cih.register.view');
    Route::get('/web/station/cih/transaction/view/{model}/{id}', [\App\Http\Controllers\CihController::class, 'transactionView'])->name('web.station.cih.transaction.view');
    Route::get('/web/station/expense/transaction/view/{id}', [\App\Http\Controllers\CihController::class, 'expenseTransactionView'])->name('web.station.expense.transaction.view');
    Route::get('/web/station/atm/transaction/view/{id}', [\App\Http\Controllers\CihController::class, 'atmTransactionView'])->name('web.station.atm.transaction.view');
    Route::post('/web/station/cih/update', [\App\Http\Controllers\CihController::class, 'changeStatus'])->name('web.station.cih.status.update');
    Route::get('/web/station/cih/verify/{id}/{station_id}/{date}', [\App\Http\Controllers\CihController::class, 'verifyCih'])->name('web.station.cih.verify');

    Route::get('/web/station/cStore-view/{reference_id}', [\App\Http\Controllers\CihController::class, 'cStoreView'])->name('web.station.cStore.detail');
    Route::get('/web/station/mechanic-view/{reference_id}', [\App\Http\Controllers\CihController::class, 'MechanicView'])->name('web.station.mechanic.detail');
    Route::get('/web/station/lotto-dc-view/{reference_id}', [\App\Http\Controllers\CihController::class, 'lottoDcView'])->name('web.station.lotto.dc.detail');
    Route::get('/web/station/lotto-pat-view/{reference_id}', [\App\Http\Controllers\CihController::class, 'lottoPatView'])->name('web.station.lotto.pat.detail');
    Route::get('/web/station/lotto-gbm-view/{reference_id}', [\App\Http\Controllers\CihController::class, 'lottoGbmView'])->name('web.station.lotto.gbm.detail');
    Route::get('/web/station/restaurant-view/{reference_id}', [\App\Http\Controllers\CihController::class, 'RestaurantMartView'])->name('web.station.restaurant.detail');
    Route::get('/web/station/restaurant-aloha-view/{reference_id}', [\App\Http\Controllers\CihController::class, 'RestaurantAlohaView'])->name('web.station.restaurant.aloha.detail');
    Route::get('/web/station/lotto-mv-online-view/{reference_id}', [\App\Http\Controllers\CihController::class, 'lottoMVOnlineView'])->name('web.station.lotto.mv.online.detail');
    Route::get('/web/station/lotto-mv-scratch-card-view/{reference_id}', [\App\Http\Controllers\CihController::class, 'lottoMVScratchCardView'])->name('web.station.lotto.mv.scratch.card.detail');

    Route::get('/web/station/atm-view/{date}/{station_id}', [\App\Http\Controllers\CihController::class, 'atmView'])->name('web.station.atm.detail');

    //==========================================Lottery Sale=====================================================

    Route::get('/web/lotto/dc/index', [\App\Http\Controllers\StationLotteryController::class, 'DcLottoOnlineIndex'])->name('web.lotto.dc.index');
    Route::get('/web/lotto/dc/list', [\App\Http\Controllers\StationLotteryController::class, 'DcLottoOnlineDataTableList'])->name('web.lotto.dc.list');
    Route::get('/web/lotto/dc/view/{id}', [\App\Http\Controllers\StationLotteryController::class, 'showDc'])->name('web.lotto.dc.view');
    Route::post('/web/lotto/dc/update', [\App\Http\Controllers\StationLotteryController::class, 'DcLottoOnlineUpdate'])->name('web.lotto.dc.update');

    Route::get('/web/lotto/pat/index', [\App\Http\Controllers\StationLotteryController::class, 'PatLottoIndex'])->name('web.lotto.pat.index');
    Route::get('/web/lotto/pat/list', [\App\Http\Controllers\StationLotteryController::class, 'PatLottoDataTableList'])->name('web.lotto.pat.list');
    Route::get('/web/lotto/pat/view/{id}', [\App\Http\Controllers\StationLotteryController::class, 'showPatLotto'])->name('web.lotto.pat.view');
    Route::post('/web/lotto/pat/update', [\App\Http\Controllers\StationLotteryController::class, 'PatLottoUpdate'])->name('web.lotto.pat.update');

    Route::get('/web/lotto/bet/mac/index', [\App\Http\Controllers\StationLotteryController::class, 'BetMachineLottoIndex'])->name('web.lotto.bet.mac.index');
    Route::get('/web/lotto/bet/mac/list', [\App\Http\Controllers\StationLotteryController::class, 'BetMachineLottoDataTableList'])->name('web.lotto.bet.mac.list');
    Route::get('/web/lotto/bet/mac/view/{id}', [\App\Http\Controllers\StationLotteryController::class, 'showBetMachineLotto'])->name('web.lotto.bet.mac.view');
    Route::post('/web/lotto/bet/mac/update', [\App\Http\Controllers\StationLotteryController::class, 'BetMachineLottoUpdate'])->name('web.lotto.bet.mac.update');

    //================================================cash_flow====================================================
    Route::get('/station/cash/cash-flow/{station_id}', [\App\Http\Controllers\StationController::class, 'siteCashFlow'])->name('station.cash.flow.index');

    //    -------------------------------------Atm cashFlow----------------------------------------
    Route::get('/web/atm/cash-flow/index', [\App\Http\Controllers\AtmController::class, 'index'])->name('web.atm.cash-flow.index');
    Route::get('/web/atm/cash-flow/list', [\App\Http\Controllers\AtmController::class, 'dataTableList'])->name('web.atm.cash-flow.list');
    Route::get('/web/atm/cash-flow/view/{id}', [\App\Http\Controllers\AtmController::class, 'show'])->name('web.atm.cash-flow.view');
    Route::post('/station/atm/cin/{id}/approve', [\App\Http\Controllers\AtmController::class, 'approve'])->name('atm.cin.operation.approve');
    Route::post('/station/atm/cin/{id}/reject', [\App\Http\Controllers\AtmController::class, 'reject'])->name('atm.cin.operation.reject');

    //    -------------------------------------general_cash cashFlow----------------------------------------
    Route::get('/web/general/cash/inflow/index', [\App\Http\Controllers\GeneralCashInflowController::class, 'index'])->name('web.general-cash.inflow.index');
    Route::get('/web/general/cash/inflow/list', [\App\Http\Controllers\GeneralCashInflowController::class, 'dataTableList'])->name('web.general-cash.inflow.list');
    Route::get('/web/general/cash/inflow/view/{id}', [\App\Http\Controllers\GeneralCashInflowController::class, 'show'])->name('web.general-cash.inflow.view');
    Route::post('/station/general/cash/inflow/{id}/approve', [\App\Http\Controllers\GeneralCashInflowController::class, 'approve'])->name('web.general-cash.inflow.approve');
    Route::post('/station/general/cash/inflow/{id}/reject', [\App\Http\Controllers\GeneralCashInflowController::class, 'reject'])->name('web.general-cash.inflow.reject');

    //    -------------------------------------Atm cashFlow----------------------------------------
    Route::get('/web/station/cih-coin/index', [\App\Http\Controllers\StationCihController::class, 'index'])->name('web.station.cih-coin.index');
    Route::get('/web/station/cih-coin/list', [\App\Http\Controllers\StationCihController::class, 'dataTableList'])->name('web.station.cih-coin.list');
    Route::get('/web/station/cih-coin/view/{id}', [\App\Http\Controllers\StationCihController::class, 'show'])->name('web.station.cih-coin.view');
    Route::post('/station/cih/sales/{id}/approve', [\App\Http\Controllers\StationCihController::class, 'approve'])->name('stationCih.sale.approve');
    Route::post('/station/cih/sales/{id}/reject', [\App\Http\Controllers\StationCihController::class, 'reject'])->name('stationCih.sale.reject');
    Route::post('/station/cih/sales/{id}/pending', [\App\Http\Controllers\StationCihController::class, 'pending'])->name('stationCih.sale.pending');

//    -------------------------------------------//UPDATE STATUS------------------------------------------
    Route::post('/station/dc/lotto/online/sales/{id}/approve', [\App\Http\Controllers\StationLotteryController::class, 'dcLottoApproveSale'])->name('dc.lotto.online.sale.approve');
    Route::post('/station/dc/lotto/online/sales/{id}/reject', [\App\Http\Controllers\StationLotteryController::class, 'dcLottoRejectSale'])->name('dc.lotto.online.sale.reject');
    Route::post('/station/dc/lotto/online/sales/{id}/pending', [\App\Http\Controllers\StationLotteryController::class, 'dcLottoPendingSale'])->name('dc.lotto.online.sale.pending');

    Route::post('/station/pat/lotto/sales/{id}/approve', [\App\Http\Controllers\StationLotteryController::class, 'patApproveSale'])->name('pat.lotto.sale.approve');
    Route::post('/station/pat/lotto/sales/{id}/reject', [\App\Http\Controllers\StationLotteryController::class, 'patRejectSale'])->name('pat.lotto.sale.reject');
    Route::post('/station/pat/lotto/sales/{id}/pending', [\App\Http\Controllers\StationLotteryController::class, 'patPendingSale'])->name('pat.lotto.sale.pending');

    Route::post('/station/gbm/lotto/sales/{id}/approve', [\App\Http\Controllers\StationLotteryController::class, 'gbmApproveSale'])->name('gbm.lotto.sale.approve');
    Route::post('/station/gbm/lotto/sales/{id}/reject', [\App\Http\Controllers\StationLotteryController::class, 'gbmRejectSale'])->name('gbm.lotto.sale.reject');
    Route::post('/station/gbm/lotto/sales/{id}/pending', [\App\Http\Controllers\StationLotteryController::class, 'gbmPendingSale'])->name('gbm.lotto.sale.pending');

    //    -------------------------------------	mechanic transactions cashFlow----------------------------------------
    Route::get('/web/station/mechanic/transaction/index', [\App\Http\Controllers\MechanicSaleController::class, 'index'])->name('web.mechanic.transaction.index');
    Route::get('/web/station/mechanic/transaction/list', [\App\Http\Controllers\MechanicSaleController::class, 'dataTableList'])->name('web.mechanic.transaction.list');
    Route::get('/web/station/mechanic/transaction/view/{id}', [\App\Http\Controllers\MechanicSaleController::class, 'show'])->name('web.mechanic.transaction.view');
    Route::post('/web/station/mechanic/transaction/destroy', [\App\Http\Controllers\MechanicSaleController::class, 'destroy'])->name('web.mechanic.transaction.destroy');
    Route::post('/station/mechanic/transaction/{id}/approve', [\App\Http\Controllers\MechanicSaleController::class, 'approve'])->name('web.mechanic.transaction.approve');
    Route::post('/station/mechanic/transaction/{id}/reject', [\App\Http\Controllers\MechanicSaleController::class, 'reject'])->name('web.mechanic.transaction.reject');
    Route::post('/station/mechanic/transaction/{id}/pending', [\App\Http\Controllers\MechanicSaleController::class, 'pending'])->name('web.mechanic.transaction.pending');

    //    -------------------------------------	CarWash cashFlow----------------------------------------
    Route::get('/web/station/car/wash/index', [\App\Http\Controllers\CarWashSaleController::class, 'index'])->name('web.car.wash.index');
    Route::get('/web/station/car/wash/list', [\App\Http\Controllers\CarWashSaleController::class, 'dataTableList'])->name('web.car.wash.list');
    Route::get('/web/station/car/wash/view/{id}', [\App\Http\Controllers\CarWashSaleController::class, 'show'])->name('web.car.wash.view');
    Route::post('/web/station/car/wash/destroy', [\App\Http\Controllers\CarWashSaleController::class, 'destroy'])->name('web.car.wash.destroy');
    Route::post('/station/car/wash/{id}/approve', [\App\Http\Controllers\CarWashSaleController::class, 'approve'])->name('web.car.wash.approve');
    Route::post('/station/car/wash/{id}/pending', [\App\Http\Controllers\CarWashSaleController::class, 'pending'])->name('web.car.wash.pending');
    Route::post('/station/car/wash/{id}/reject', [\App\Http\Controllers\CarWashSaleController::class, 'reject'])->name('web.car.wash.reject');

    //    -------------------------------------	Virginia Online cashFlow----------------------------------------
    Route::get('/web/station/virginia/index', [\App\Http\Controllers\StationLotteryController::class, 'VirginiaLottoIndex'])->name('web.virginia.index');
    Route::get('/web/station/virginia/list', [\App\Http\Controllers\StationLotteryController::class, 'VirginiaDataTableList'])->name('web.virginia.list');
    Route::get('/web/station/virginia/view/{id}', [\App\Http\Controllers\StationLotteryController::class, 'showMvLotto'])->name('web.virginia.view');

    Route::post('/station/mv/{id}/approve', [\App\Http\Controllers\StationLotteryController::class, 'mvApproveSale'])->name('web.maryland.virginia.approve');
    Route::post('/station/mv/{id}/reject', [\App\Http\Controllers\StationLotteryController::class, 'mvRejectSale'])->name('web.maryland.virginia.reject');
    Route::post('/station/mv/{id}/pending', [\App\Http\Controllers\StationLotteryController::class, 'mvPendingSale'])->name('web.maryland.virginia.pending');

    //    -------------------------------------	Virginia scratch card cashFlow----------------------------------------
    Route::get('/web/station/vir/scratch/card/index', [\App\Http\Controllers\StationLotteryController::class, 'VirginiaScratchCardLottoIndex'])->name('web.virginia.scratch.card.index');
    Route::get('/web/station/vir/scratch/card/list', [\App\Http\Controllers\StationLotteryController::class, 'VirginiaScratchCardDataTableList'])->name('web.virginia.scratch.card.list');
    Route::get('/web/station/vir/scratch/card/view/{id}', [\App\Http\Controllers\StationLotteryController::class, 'showScratchCardLotto'])->name('web.virginia.scratch.card.view');

    Route::post('/station/vir/scratch/card/{id}/approve', [\App\Http\Controllers\StationLotteryController::class, 'scratchCardApproveSale'])->name('web.mv.scratch.card.approve');
    Route::post('/station/vir/scratch/card/{id}/reject', [\App\Http\Controllers\StationLotteryController::class, 'scratchCardRejectSale'])->name('web.mv.scratch.card.reject');
    Route::post('/station/vir/scratch/card/{id}/pending', [\App\Http\Controllers\StationLotteryController::class, 'scratchCardPendingSale'])->name('web.mv.scratch.card.pending');

    //    -------------------------------------	MaryLand Online cashFlow----------------------------------------
    Route::get('/web/station/maryland/index', [\App\Http\Controllers\StationLotteryController::class, 'MaryLandLottoIndex'])->name('web.maryLand.index');
    Route::get('/web/station/maryland/list', [\App\Http\Controllers\StationLotteryController::class, 'MaryLandDataTableList'])->name('web.maryLand.list');
    Route::get('/web/station/maryland/view/{id}', [\App\Http\Controllers\StationLotteryController::class, 'showMvLotto'])->name('web.maryland.view');

    //    -------------------------------------	MaryLand scratch card cashFlow----------------------------------------
    Route::get('/web/station/mary/scratch/card/index', [\App\Http\Controllers\StationLotteryController::class, 'MaryLandScratchCardLottoIndex'])->name('web.maryLand.scratch.card.index');
    Route::get('/web/station/mary/scratch/card/list', [\App\Http\Controllers\StationLotteryController::class, 'MaryLandScratchCardDataTableList'])->name('web.maryLand.scratch.card.list');
    Route::get('/web/station/mary/scratch/card/view/{id}', [\App\Http\Controllers\StationLotteryController::class, 'showScratchCardLotto'])->name('web.maryland.scratch.card.view');
    Route::get('/web/station/mv/scratch/card/view/{id}', [\App\Http\Controllers\StationLotteryController::class, 'showScratchCardLotto'])->name('web.mv.scratch.card.view');

    //    -------------------------------------	MaryLand scratch card cashFlow----------------------------------------

    Route::get('/web/station/payroll/index', [\App\Http\Controllers\PayrollController::class, 'index'])->name('payroll.index');
    Route::get('/web/station/payroll/list', [\App\Http\Controllers\PayrollController::class, 'dataTableList'])->name('payroll.list');
    Route::get('/web/station/payroll/view/{id}', [\App\Http\Controllers\PayrollController::class, 'view'])->name('payroll.view');
    Route::post('/web/station/payroll/upload', [\App\Http\Controllers\PayrollController::class, 'store'])->name('payroll.save');
    Route::post('/web/station/payroll/pay-cash', [\App\Http\Controllers\PayrollController::class, 'processCashPayment'])->name('payroll.pay-cash');
    Route::get('/web/station/payroll/download-sample-csv', [\App\Http\Controllers\PayrollController::class, 'downloadSampleCsv'])->name('payroll.download-sample-csv');

});


require __DIR__.'/auth.php';
require __DIR__.'/api.php';


