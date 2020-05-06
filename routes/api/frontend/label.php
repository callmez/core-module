<?php
/**
 * Created by PhpStorm.
 * User: aoxiang
 * Date: 2020-05-06
 * Time: 12:09
 */

use Modules\Core\src\Http\Controllers\Frontend\Api\LabelController;

Route::group([
    'prefix'     => 'v1/label',
    'as'         => 'label.',
//    'middleware' => ['auth:admin'],
], function () {
    Route::get('/', [LabelController::class, 'index'])->name('index');
    Route::get('/{label}', [LabelController::class, 'info'])->name('info');
});
