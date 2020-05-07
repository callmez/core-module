<?php
/**
 * Created by PhpStorm.
 * User: aoxiang
 * Date: 2020-05-06
 * Time: 14:33
 */

use Modules\Core\Http\Controllers\Frontend\Api\NoticeController;

Route::group([
    'prefix'     => 'v1/notice',
    'as'         => 'notice.',
    'middleware' => ['auth:sanctum'],
], function () {
    Route::get('/', [NoticeController::class, 'index'])->name('index');
    Route::get('/{id}', [NoticeController::class, 'info'])->name('info');
});
