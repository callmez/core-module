<?php

use Modules\Core\Http\Controllers\Frontend\Api\LabelController;

Route::group([
    'prefix'     => 'v1/label',
    'as'         => 'label.',
    'middleware' => ['auth:sanctum'],
], function () {
    Route::get('/', [LabelController::class, 'index'])->name('index');
    Route::get('/{label}', [LabelController::class, 'info'])->name('info');
});
