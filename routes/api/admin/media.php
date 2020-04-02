<?php

use Modules\Core\Http\Controllers\Admin\Api\Media\UploadController;

Route::group([
    'prefix' => 'v1/media',
    'as' => 'media.',
    'namespace' => 'Media',
    'middleware' => ['auth:admin']
], function () {
    Route::get('/', [UploadController::class, 'index'])->name('index');
    Route::post('upload', [UploadController::class, 'upload'])->name('upload');
});
