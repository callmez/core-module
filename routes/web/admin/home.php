<?php

use Modules\Core\Http\Controllers\Admin\DashboardController;
use Modules\Core\Http\Controllers\Admin\WelcomeController;

// All route names are prefixed with 'admin.'.
Route::group(['middleware' => ['auth:admin']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('welcome', [WelcomeController::class, 'index'])->name('welcome');
});
