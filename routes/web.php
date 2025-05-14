<?php

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

                    Route::post('/update_doucuments','update_documents')->name('dashboard_doc_update.user');


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
        });
// });


// Route::get('/users',[UserController::class, 'index'])->name('dashboard_list.user')->middleware('LogsMiddleware');

// Route::get('/users/details/{id}',function(){ return view('modules.users.view-user-details');})->name('dashboard_details.user');

// Route::get('/users/create',function(){ return view('modules.users.create-user');})->name('dashboard_create.user');

// Route::post('/users/create', [UserController::class, 'create'])->name('dashboard_store.user');

// Route::get('/users/edit/{id}',[UserController::class,'detail'])->name('dashboard_edit.user');

// Route::put('/users/update', [UserController::class, 'update'])->name('dashboard_update.user');

// Route::delete('/users/destroy', [UserController::class, 'destroy'])->name('dashboard_destroy.user');



