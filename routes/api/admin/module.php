<?php

use Modules\Core\Http\Controllers\Admin\Api\Module\ModuleController;

// Module Management
Route::group([
    'namespace' => 'Module',
    'as'        => 'module.',
    'prefix'    => 'v1/modules'
], function () {

    Route::get('/', [ModuleController::class, 'index'])->name('modules');

    Route::group(['prefix' => '{module}'], function () {
        Route::post('/enable', [ModuleController::class, 'enable'])->name('enable');
        Route::post('/disable', [ModuleController::class, 'disable'])->name('disable');
    });

});
