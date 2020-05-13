<?php


use Modules\Core\Http\Controllers\Frontend\Api\Auth\LoginController;
use Modules\Core\Http\Controllers\Frontend\Api\Auth\RegisterController;
use Modules\Core\Http\Controllers\Frontend\Api\Auth\UserController;
use Modules\Core\Http\Controllers\Frontend\Api\Auth\LogoutController;
use Modules\Core\Http\Controllers\Frontend\Api\Auth\VerifyController;
use Modules\Core\Http\Controllers\Frontend\Api\Auth\ResetController;
use Modules\Core\Http\Controllers\Frontend\Api\Auth\ChangePasswordController;

/*
 * Frontend Access Controllers
 * All route names are prefixed with 'frontend.auth'.
 */
Route::group([
    'namespace' => 'Auth',
    'as' => 'auth.'
], function () {
    Route::group(['middleware' => 'guest'], function () {

        Route::post('v1/login', [LoginController::class, 'loginByGuessString'])->name('login'); // 密码登录
        Route::post('v1/register', [RegisterController::class, 'register'])->name('register'); // 用户注册
        Route::get('v1/reset/password', [ResetController::class, 'requestResetPasswordSms'])->name('reset.password.sms'); //获取重置密码短信
        Route::post('v1/reset/password', [ResetController::class, 'resetPassword'])->name('reset.password.post'); //重置密码

//
//        // Socialite Routes
//        Route::get('login/{provider}', [SocialLoginController::class, 'login'])->name('social.login');
//        Route::get('login/{provider}/callback', [SocialLoginController::class, 'login']);
//
//        // Registration Routes
//        Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
//        Route::post('register', [RegisterController::class, 'register'])->name('register.post');
//
//        // Confirm Account Routes
//        Route::get('account/confirm/{token}', [ConfirmAccountController::class, 'confirm'])->name('account.confirm');
//        Route::get('account/confirm/resend/{uuid}', [ConfirmAccountController::class, 'sendConfirmationEmail'])->name('account.confirm.resend');
//
//        // Password Reset Routes
//        Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.email');
//        Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email.post');

//        Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');
//        Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');
    });
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('v1/logout', [LogoutController::class, 'logout'])->name('logout'); // 退出登录
    });

    Route::group([
        'prefix' => 'v1/auth',
        'middleware' => ['auth:sanctum'],
    ], function () {
        Route::get('info', [UserController::class, 'info'])->name('info'); // 登录会员信息

        Route::get('reset/pay_password', [ResetController::class, 'requestResetPayPasswordSms'])->name('reset.pay_password.sms'); //获取重置支付密码短信
        Route::post('reset/pay_password', [ResetController::class, 'resetPayPassword'])->name('reset.pay_password.post'); // 重置支付密码
        Route::post('change/pay_password', [ResetController::class, 'changePayPassword'])->name('change.pay_password');//修改支付密码
//
//        Route::get('set/mobile', [VerifyController::class, 'requestSetMobileSms'])->name('set.mobile.sms'); // 获取设置手机号短信
//        Route::post('set/mobile', [VerifyController::class, 'setMobile'])->name('set.mobile'); // 设置手机号


        Route::post('reset/email', [ResetController::class, 'requestResetEmail'])->name('reset.email'); // 验证邮箱请求
        Route::post('reset/mobile', [ResetController::class, 'requestResetMobile'])->name('reset.mobile'); // 修改手机号请求
        Route::post('verify/email', [VerifyController::class, 'verifyEmail'])->name('verify.email'); // 修改邮箱
        Route::post('verify/mobile', [VerifyController::class, 'verifyMobile'])->name('verify.mobile'); // 设置绑定手机号
        Route::get('verify/mobile', [VerifyController::class, 'requestSetMobileSms'])->name('verify.mobile'); // 验证绑定手机号短信
        Route::post('change/password', [ResetController::class, 'changePassword'])->name('change.password');//修改登录密码
//        // These routes can not be hit if the password is expired
//        Route::group(['middleware' => 'password_expires'], function () {
//            // Change Password Routes
//            Route::patch('password/update', [UpdatePasswordController::class, 'update'])->name('password.update');
//        });
//
//        // Password expired routes
//        Route::get('password/expired', [PasswordExpiredController::class, 'expired'])->name('password.expired');
//        Route::patch('password/expired', [PasswordExpiredController::class, 'update'])->name('password.expired.update');
    });
});
