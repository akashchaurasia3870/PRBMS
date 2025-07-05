<?php

use App\Http\Controllers\AttendenceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\RoleController;
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
            Route::prefix('attendence')->group(function(){
                Route::controller(AttendenceController::class)->group(function(){
                    Route::get('/', 'index')->name('dashboard_list.user_attendence');

                    Route::get('/details/{id}','get_user_attendence_details')->name('dashboard_details.user_attendence');

                    Route::get('/mark','get_user_list')->name('dashboard_mark.mark_attendence');

                    Route::post('/create','mark_attendence')->name('dashboard_store.user_attendence');

                    Route::get('/edit/{id}','user_attendence_details')->name('dashboard_edit.user_attendence');

                    Route::put('/update','update_user_attendence_details')->name('dashboard_update.user_attendence');

                    Route::post('/mark_all_attendence','mark_all_attendence')->name('dashboard_store.mark_all_attendence');

                    // Route::delete('/destroy',  'destroy_attendence')->name('dashboard_destroy.user_attendence');

                    // Route::post('/update_attendence','update_attendence')->name('dashboard_update.user_attendence');
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
        });



