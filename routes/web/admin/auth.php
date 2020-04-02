<?php

use Modules\Core\Http\Controllers\Admin\Auth\LoginController;
use Modules\Core\Http\Controllers\Admin\Auth\Role\RoleController;
use Modules\Core\Http\Controllers\Admin\Auth\User\UserConfirmationController;
use Modules\Core\Http\Controllers\Admin\Auth\User\UserController;
use Modules\Core\Http\Controllers\Admin\Auth\User\UserPasswordController;
use Modules\Core\Http\Controllers\Admin\Auth\User\UserSessionController;
use Modules\Core\Http\Controllers\Admin\Auth\User\UserSocialController;
use Modules\Core\Http\Controllers\Admin\Auth\User\UserStatusController;

// All route names are prefixed with 'admin.auth'.
Route::group([
    'namespace' => 'Auth',
    'as'        => 'auth.',
], function () {

    Route::group(['middleware' => ['use_guard:admin', 'guest']], function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login'); // 密码登录
        Route::post('login', [LoginController::class, 'login'])->name('login.post'); // 提交密码登录
    });

    Route::group(['prefix' => 'auth', 'middleware' => ['admin']], function () {

        Route::post('logout', [LoginController::class, 'logout'])->name('logout'); // 退出登录

        // User Management
//        Route::group(['namespace' => 'User'], function () {
//            // User Status'
//            Route::get('user/deactivated', [UserStatusController::class, 'getDeactivated'])->name('user.deactivated');
//            Route::get('user/deleted', [UserStatusController::class, 'getDeleted'])->name('user.deleted');
//
//            // User CRUD
//            Route::get('user', [UserController::class, 'index'])->name('user.index');
//            Route::get('user/create', [UserController::class, 'create'])->name('user.create');
//            Route::post('user', [UserController::class, 'store'])->name('user.store');
//
//            // Specific User
//            Route::group(['prefix' => 'user/{user}'], function () {
//                // User
//                Route::get('/', [UserController::class, 'show'])->name('user.show');
//                Route::get('edit', [UserController::class, 'edit'])->name('user.edit');
//                Route::patch('/', [UserController::class, 'update'])->name('user.update');
//                Route::delete('/', [UserController::class, 'destroy'])->name('user.destroy');
//
//                // Account
//                Route::get('account/confirm/resend',
//                    [UserConfirmationController::class, 'sendConfirmationEmail'])->name('user.account.confirm.resend');
//
//                // Status
//                Route::get('mark/{status}',
//                    [UserStatusController::class, 'mark'])->name('user.mark')->where(['status' => '[0,1]']);
//
//                // Social
//                Route::delete('social/{social}/unlink',
//                    [UserSocialController::class, 'unlink'])->name('user.social.unlink');
//
//                // Confirmation
//                Route::get('confirm', [UserConfirmationController::class, 'confirm'])->name('user.confirm');
//                Route::get('unconfirm', [UserConfirmationController::class, 'unconfirm'])->name('user.unconfirm');
//
//                // Password
//                Route::get('password/change', [UserPasswordController::class, 'edit'])->name('user.change-password');
//                Route::patch('password/change',
//                    [UserPasswordController::class, 'update'])->name('user.change-password.post');
//
//                // Session
//                Route::get('clear-session', [UserSessionController::class, 'clearSession'])->name('user.clear-session');
//
//                // Deleted
//                Route::get('delete', [UserStatusController::class, 'delete'])->name('user.delete-permanently');
//                Route::get('restore', [UserStatusController::class, 'restore'])->name('user.restore');
//            });
//        });

        // Role Management
        Route::group(['namespace' => 'Role'], function () {
            Route::get('roles', [RoleController::class, 'index'])->name('roles');
            Route::get('roles/create', [RoleController::class, 'create'])->name('role.create');
            Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('role.edit');
        });
    });
});
