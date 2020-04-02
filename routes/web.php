<?php

use Modules\Core\Http\Controllers\LanguageController;

Route::get('captcha/{config?}', 'CaptchaController@captcha')->name('captcha');
/*
 * Global Routes
 * Routes that are used between both frontend and admin.
 */

// Switch between the included languages
Route::get('lang/{lang}', [LanguageController::class, 'swap']);

/*
 * Frontend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    include_route_files(__DIR__.'/web/frontend/');
});


/*
     * Admin Routes
     * Namespaces indicate folder structure
     */
Route::group([
    'namespace' => 'Admin',
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => [\Modules\Core\Http\Middleware\UseGuard::class . ':admin'],
], function () {
    /*
     * These routes need view-admin permission
     * (good if you want to allow more than one group in the admin,
     * then limit the admin features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     * These routes can not be hit if the password is expired
     */
    include_route_files(__DIR__.'/web/admin/');
});
