<?php

use Modules\Core\Http\Controllers\Frontend\Auth\EmailVerifyController;

Route::group([
    'namespace' => 'Auth',
    'as'        => 'auth.',
    'prefix'    => 'auth'
], function () {
    Route::get('email/verify', [EmailVerifyController::class, 'index'])->name('email.verify'); // 确认邮箱验证
});
