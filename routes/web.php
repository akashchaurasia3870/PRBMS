<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalaryStructureController;
use App\Http\Controllers\PayrollReceiptController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

// Route::middleware(['logs'])->group(function() {
        Route::prefix('dashboard')->group(function(){
            Route::prefix('users')->group(function(){
                Route::controller(UserController::class)->group(function(){
                    Route::get('/', 'index')->name('dashboard_list.user');

                    Route::get('/details/{id}','get_user_details')->name('dashboard_details.user');

                    Route::get('/create',function(){ return view('modules.users.create-user');})->name('dashboard_create.user');

                    Route::post('/create','create')->name('dashboard_store.user');

                    Route::get('/edit/{id}','detail')->name('dashboard_edit.user');

                    Route::put('/update','update')->name('dashboard_update.user');

                    Route::delete('/destroy',  'destroy')->name('dashboard_destroy.user');

                    Route::post('/update_contact','update_contact')->name('dashboard_contact_update.user');

                    Route::post('/update_doucuments','update_documents')->name('dashboard_doc_update.user')->middleware('file.upload');


                });
            });
            Route::prefix('roles')->group(function(){
                Route::controller(RoleController::class)->group(function(){

                    Route::get('/', 'index')->name('dashboard_list.roles');

                    // Route::get('/details/{id}',function(){ return view('modules.roles.view-user-details');})->name('dashboard_details.roles');

                    Route::get('/create',function(){ return view('modules.roles.create-roles');})->name('dashboard_create.roles');

                    Route::post('/create','create')->name('dashboard_store.roles');

                    Route::get('/edit/{id}','detail')->name('dashboard_edit.roles');

                    Route::get('/add_users/{id}/{lvl}/{role_name}','add_users_list')->name('dashboard_add_users.roles');

                    Route::post('/add_users_role','add_users_data')->name('dashboard_add_users_roles.roles');

                    Route::put('/update','update')->name('dashboard_update.roles');

                    Route::delete('/destroy',  'destroy')->name('dashboard_destroy.roles');

                    Route::post('/destroy_user_role',  'destroy_user_role')->name('dashboard_remove_users_roles.roles');

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
                    Route::get('/', 'index')->name('dashboard_leave.leave_request');

                    Route::get('/apply','leave_request_view')->name('dashboard_leave.leave_request_view');
                    
                    Route::post('/apply','create_leave_request')->name('dashboard_leave.create_leave_request');

                    Route::get('/details/{id}','get_leave_info')->name('dashboard_leave.get_leave_info');
                    
                    Route::post('/approve_leave_status/{id}','approve_leave_status')->name('dashboard_leave.approve_leave_status');
                    
                    Route::post('/reject_leave_status/{id}','reject_leave_status')->name('dashboard_leave.reject_leave_status');
                    
                    Route::put('/edit/{id}','edit_leave_info')->name('dashboard_leave.edit_leave_info');

                    Route::delete('/delete/{id}','destroy_leave_info')->name('dashboard_leave.destroy_leave_info');
                });
            });
            Route::prefix('salary-structure')->group(function(){
                Route::controller(SalaryStructureController::class)->group(function(){
                    Route::get('/', 'index')->name('dashboard_salary.index');

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

        });



