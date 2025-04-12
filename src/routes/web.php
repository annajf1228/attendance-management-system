<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserLoginController;
use App\Http\Controllers\User\StaffController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserWorkController;

// ユーザー用ログインルート
Route::prefix('login')->name('user.')->middleware(['redirect.auth:web,user.top'])->group(function () {
    Route::get('/', [UserLoginController::class, 'index'])->name('login.index');
    Route::post('/', [UserLoginController::class, 'login'])->name('login');
});

// ユーザー用ログイン後ルート
Route::middleware(['auth:web,user.login.index'])->group(function () {
    Route::get('/', [StaffController::class, 'top'])->name('user.top');
    Route::post('/logout', [UserLoginController::class, 'logout'])->name('user.logout');
    Route::prefix('staff')->name('user.staff.')->group(function () {
        Route::get('/index', [StaffController::class, 'index'])->name('index');
        Route::post('/store-clock-in', [StaffController::class, 'storeClockIn'])->name('store-clock-in');
        Route::post('/update-clock-out', [StaffController::class, 'updateClockOut'])->name('update-clock-out');
        Route::get('/edit/{work_record_id}', [StaffController::class, 'edit'])->name('edit');
        Route::post('/update', [StaffController::class, 'update'])->name('update');
    });
});

// 管理者用ログインルート
Route::prefix('admin')->name('admin.')->middleware(['redirect.auth:admin,admin.index'])->group(function () {
    Route::get('/login', [AdminLoginController::class, 'index'])->name('login.index');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login');
});

// 管理者用ログイン後ルート
Route::prefix('admin')->name('admin.')->middleware(['auth:admin,admin.login.index'])->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('/top', 'top')->name('top');
        Route::get('/index', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update', 'update')->name('update');
        Route::delete('/destroy', 'destroy')->name('destroy');
    });
    Route::prefix('user')->name('user.')->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('/index', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/show/{id}', 'show')->name('show');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update', 'update')->name('update');
            Route::delete('/destroy', 'destroy')->name('destroy');
        });
    });
    Route::prefix('user-work')->name('user-work.')->group(function () {
        Route::controller(UserWorkController::class)->group(function () {
            Route::get('/view', 'view')->name('view');
            Route::get('/index', 'index')->name('index');
            Route::get('/edit/{work_record_id}', 'edit')->name('edit');
            Route::post('/update', 'update')->name('update');
            Route::get('/download-csv', 'downloadCsv')->name('download-csv');
        });
    });
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
});
