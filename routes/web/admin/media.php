<?php

use Modules\Core\Http\Controllers\Admin\MediaController;

// All route names are prefixed with 'admin.'.
Route::group([
    'middleware' => ['auth:admin'],
    'prefix' => 'media',
    'as' => 'media.'
], function () {
    Route::get('/', [MediaController::class, 'index'])->name('index');
});
