<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('captcha/{config?}', 'CaptchaController@captchaApi')->name('captcha');

/*
 * Frontend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Frontend\Api', 'as' => 'frontend.api.'], function () {
    /*
     * These routes need view-admin permission
     * (good if you want to allow more than one group in the admin,
     * then limit the admin features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     * These routes can not be hit if the password is expired
     */
    include_route_files(__DIR__.'/api/frontend/');
});


/*
 * Admin Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Admin\Api', 'prefix' => 'admin', 'as' => 'admin.api.'], function () {
    /*
     * These routes need view-admin permission
     * (good if you want to allow more than one group in the admin,
     * then limit the admin features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     * These routes can not be hit if the password is expired
     */
    include_route_files(__DIR__.'/api/admin/');
});
