<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalaryStructureController;
use App\Http\Controllers\PayrollReceiptController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ExpenseTrackerController;
use App\Http\Controllers\ExpenseTrackerTypeController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', function () {
            return view('dashboard');
        })->name('dashboard');
    });
});

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->name('logs');


// Route::middleware(['logs'])->group(function() {
Route::prefix('dashboard')->group(function(){
    Route::prefix('users')->group(function(){
        Route::controller(UserController::class)->group(function(){
            // View routes
            Route::get('/', 'getIndexView')->name('dashboard_list.user');
            Route::get('/details/{id}', 'getDetailView')->name('dashboard_details.user');
            Route::get('/create', 'getCreateView')->name('dashboard_create.user');
            Route::get('/edit/{id}', 'getEditView')->name('dashboard_edit.user');

            // Form submission routes
            Route::post('/create', 'submitCreateForm')->name('dashboard_store.user');
            Route::put('/update', 'submitUpdateForm')->name('dashboard_update.user');
            Route::delete('/destroy', 'submitDeleteForm')->name('dashboard_destroy.user');

            // Data API routes
            Route::post('/data/index', 'getIndexData')->name('dashboard_data.user');
            Route::post('/data/show', 'getDetailData')->name('dashboard_detail_data.user');
        });
    });
    Route::prefix('roles')->group(function(){
        Route::controller(RoleController::class)->group(function(){
            // View routes
            Route::get('/', 'getIndexView')->name('dashboard_list.roles');
            Route::get('/create', 'getCreateView')->name('dashboard_create.roles');
            Route::get('/edit/{id}', 'getEditView')->name('dashboard_edit.roles');
            Route::get('/show/{id}', 'getDetailView')->name('dashboard_show.roles');

            // Form submission routes
            Route::post('/create', 'submitCreateForm')->name('dashboard_store.roles');
            Route::put('/update', 'submitUpdateForm')->name('dashboard_update.roles');
            Route::delete('/destroy', 'submitDeleteForm')->name('dashboard_destroy.roles');

            // User management routes (legacy)
            Route::get('/add_users/{id}/{lvl}/{role_name}', 'add_users_list')->name('dashboard_add_users.roles');
            Route::post('/add_users_role', 'add_users_data')->name('dashboard_add_users_roles.roles');
            Route::post('/destroy_user_role', 'destroy_user_role')->name('dashboard_remove_users_roles.roles');

            // Data API routes
            Route::post('/data/index', 'getIndexData')->name('dashboard_data.roles');
            Route::post('/data/show', 'getDetailData')->name('dashboard_detail_data.roles');
        });
    });
    Route::prefix('attendance')->group(function(){
        Route::controller(AttendanceController::class)->group(function(){
            Route::get('/', 'index')->name('dashboard_list.user_attendance');

            Route::get('/details/{id}','get_user_attendance_details')->name('dashboard_details.user_attendance');

            Route::get('/mark','get_user_list')->name('dashboard_mark.mark_attendance');

            Route::post('/create','mark_attendance')->name('dashboard_store.user_attendance');

            Route::get('/edit/{id}','user_attendance_details')->name('dashboard_edit.user_attendance');

            Route::put('/update','update_user_attendance_details')->name('dashboard_update.user_attendance');

            Route::post('/mark_all_attendance','mark_all_attendance')->name('dashboard_store.mark_all_attendance');

            // Route::delete('/destroy',  'destroy_attendance')->name('dashboard_destroy.user_attendance');

            // Route::post('/update_attendance','update_attendance')->name('dashboard_update.user_attendance');
        });
    });
    Route::prefix('leave')->group(function(){
        Route::controller(LeaveController::class)->group(function(){
            Route::get('/', 'index')->name('dashboard_leave.index');
            Route::get('/dashboard', 'dashboard')->name('dashboard_leave.dashboard');

            Route::get('/apply','leave_request_view')->name('dashboard_leave.leave_request_view');
            
            Route::post('/apply','create_leave_request')->name('dashboard_leave.create_leave_request');

            Route::get('/details/{id}','get_leave_info')->name('dashboard_leave.get_leave_info');
            
            Route::post('/approve_leave_status/{id}','approve_leave_status')->name('dashboard_leave.approve_leave_status');
            
            Route::post('/reject/{id}','reject_leave_status')->name('dashboard_leave.reject_leave_status');
            
            Route::put('/edit/{id}','edit_leave_info')->name('dashboard_leave.edit_leave_info');

            Route::delete('/delete/{id}','destroy_leave_info')->name('dashboard_leave.destroy_leave_info');
        });
    });
    Route::prefix('salary-structure')->group(function(){
        Route::controller(SalaryStructureController::class)->group(function(){
            Route::get('/', 'index')->name('dashboard_salary.index');
            
            Route::get('/dashboard', 'dashboard')->name('dashboard_salary.dashboard');

            Route::get('/create','create')->name('dashboard_salary.create');
            
            Route::post('/store','store')->name('dashboard_salary.store');

            Route::get('/edit/{id}','edit')->name('dashboard_salary.edit');
            
            Route::post('/update/{id}','update')->name('dashboard_salary.update');
            
            Route::post('/delete/{id}','delete')->name('dashboard_salary.delete');
        });
    });
    Route::prefix('payroll-receipt')->group(function () {
        Route::controller(PayrollReceiptController::class)->group(function () {

            // List all payroll receipts
            Route::get('/', 'index')->name('dashboard_payroll.index');
            
            // Dashboard
            Route::get('/dashboard', 'dashboard')->name('dashboard_payroll.dashboard');

            // Show payroll receipt details
            Route::get('/{id}', 'show')->name('dashboard_payroll.show');

            // Generate payroll form
            Route::get('/generate/form', 'generateForm')->name('dashboard_payroll.generateForm');

            // Process payroll generation
            Route::post('/generate', 'generatePayroll')->name('dashboard_payroll.generatePayroll');

            // Mark as paid
            Route::post('/{id}/pay', 'markAsPaid')->name('dashboard_payroll.markAsPaid');

            // Edit form
            Route::get('/{id}/edit', 'edit')->name('dashboard_payroll.edit');

            // Update payroll receipt
            Route::post('/{id}/update', 'update')->name('dashboard_payroll.update');

            // Soft delete payroll receipt
            Route::post('/{id}/delete', 'destroy')->name('dashboard_payroll.destroy');

            // Export payroll (future)
            Route::get('/export', 'export')->name('dashboard_payroll.export');

        });
    });
    // Inventory routes following the same pattern as expenses
    Route::prefix('inventory')->group(function () {
        // Dashboard and audit logs
        Route::get('/dashboard', [InventoryController::class, 'getDashboard'])->name('inventory.dashboard');
        Route::get('/audit-logs', [InventoryController::class, 'getAuditLogs'])->name('inventory.audit.logs');
        
        // New routes following expense pattern
        Route::get('/v1/new', [InventoryController::class, 'getCreateView'])->name('inventory.v1.new');
        
        // Stock operations
        Route::post('/{id}/stock-in', [InventoryController::class, 'stockIn'])->name('inventory.stockIn');
        Route::post('/{id}/stock-out', [InventoryController::class, 'stockOut'])->name('inventory.stockOut');
        Route::get('/{id}/logs', [InventoryController::class, 'viewLogs'])->name('inventory.logs');
    });
    
    // Category audit logs
    Route::get('/category/audit-logs', [CategoryController::class, 'getAuditLogs'])->name('category.audit.logs');
    
    // Legacy resource routes for backward compatibility  
    Route::resource('inventory', InventoryController::class);

    // Category routes following the same pattern as expense types
    Route::prefix('category')->group(function () {
        // Audit logs
        Route::get('/audit-logs', [CategoryController::class, 'getAuditLogs'])->name('category.audit.logs');
        
        // api to get views 
        Route::get('/v1/index', [CategoryController::class, 'getIndexView'])->name('category.v1.index');
        Route::get('/v1/new', [CategoryController::class, 'getCreateView'])->name('category.v1.new');
        Route::get('/v1/edit/{id}', [CategoryController::class, 'getEditView'])->name('category.v1.edit');
        Route::get('/v1/show/{id}', [CategoryController::class, 'getDetailView'])->name('category.v1.show');

        // api to submit forms 
        Route::post('/v2/new', [CategoryController::class, 'submitCreateForm'])->name('category.v2.new');
        Route::post('/v2/edit/{id}', [CategoryController::class, 'submitUpdateForm'])->name('category.v2.edit');
        Route::post('/v2/delete/{id}', [CategoryController::class, 'submitDeleteForm'])->name('category.v2.delete');

        // api to get data 
        Route::post('/v3/index', [CategoryController::class, 'getIndexData'])->name('category.v3.index');
        Route::post('/v3/show', [CategoryController::class, 'getDetailData'])->name('category.v3.show');
    });
    
    // Legacy resource routes for backward compatibility
    Route::resource('category', CategoryController::class);

    // All logs, with optional query params for filtering:
    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit_logs.index');

    // Logs for a specific model/resource (e.g. /audit-logs/inventory/1)
    Route::get('audit-logs/{model_type}/{model_id}', [AuditLogController::class, 'logsForResource'])->name('audit_logs.resource');

    // View details of a single audit log entry
    Route::get('audit-log/{id}', [AuditLogController::class, 'show'])->name('audit_logs.show');

    Route::prefix('expenses')->group(function () {
        // api to get views 
        Route::get('/v1/dashboard', [ExpenseTrackerController::class, 'getDashboard'])->name('expense.v1.dashboard');
        Route::get('/v1/audit-logs', [ExpenseTrackerController::class, 'getAuditLogs'])->name('expense.audit.logs');
        Route::get('/v1/index', [ExpenseTrackerController::class, 'getIndexView'])->name('expense.v1.index');
        Route::get('/v1/new', [ExpenseTrackerController::class, 'getCreateView'])->name('expense.v1.new');
        Route::get('/v1/edit/{id}', [ExpenseTrackerController::class, 'getEditView'])->name('expense.v1.edit');
        Route::get('/v1/show/{id}', [ExpenseTrackerController::class, 'getDetailView'])->name('expense.v1.show');

        // api to submit forms 
        Route::post('/v2/new', [ExpenseTrackerController::class, 'submitCreateForm'])->name('expense.v2.new');
        Route::post('/v2/edit/{id}', [ExpenseTrackerController::class, 'submitUpdateForm'])->name('expense.v2.edit');
        Route::post('/v2/delete/{id}', [ExpenseTrackerController::class, 'submitDeleteForm'])->name('expense.v2.delete');

        // api to get data 
        Route::post('/v3/index', [ExpenseTrackerController::class, 'getIndexData'])->name('expense.v3.index');
        Route::post('/v3/show', [ExpenseTrackerController::class, 'getDetailData'])->name('expense.v3.show');

    });

    Route::prefix('expense_type')->group(function () {

        // api to get views 
        Route::get('/v1/index', [ExpenseTrackerTypeController::class, 'getIndexView'])->name('expense_type.v1.index');
        Route::get('/v1/new', [ExpenseTrackerTypeController::class, 'getCreateView'])->name('expense_type.v1.new');
        Route::get('/v1/edit/{id}', [ExpenseTrackerTypeController::class, 'getEditView'])->name('expense_type.v1.edit');
        Route::get('/v1/show/{id}', [ExpenseTrackerTypeController::class, 'getDetailView'])->name('expense_type.v1.show');

        // api to submit forms 
        Route::post('/v2/new', [ExpenseTrackerTypeController::class, 'submitCreateForm'])->name('expense_type.v2.new');
        Route::post('/v2/edit/{id}', [ExpenseTrackerTypeController::class, 'submitUpdateForm'])->name('expense_type.v2.edit');
        Route::post('/v2/delete/{id}', [ExpenseTrackerTypeController::class, 'submitDeleteForm'])->name('expense_type.v2.delete');

        // api to get data 
        Route::post('/v3/index', [ExpenseTrackerTypeController::class, 'getIndexData'])->name('expense_type.v3.index');
        Route::post('/v3/show', [ExpenseTrackerTypeController::class, 'getDetailData'])->name('expense_type.v3.show');
    });
});

// Route::resource('users', UserModuleController::class);

// Route::get('users/update_contact', [UserModuleController::class, 'update_contact'])->name('users.update_contact');

// Route::get('users/update_documents', [UserModuleController::class, 'update_documents'])->name('users.update_documents')->middleware('file.upload');




