<?php

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
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    Route::get('/users/create', [UserController::class, 'create'])->name('users_cstm.create');
    Route::get('/users/edit', [UserController::class, 'edit'])->name('users_cstm.edit');
    Route::get('/users', [UserController::class, 'index'])->name('users_cstm.index');
    Route::get('/users/details/{id}', [UserController::class, 'show'])->name('users_cstm.details');
    Route::delete('/users/destroy/{id}', [UserController::class, 'destroy'])->name('users_cstm.destroy');
});
