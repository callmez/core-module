<?php

use Modules\Core\Http\Controllers\Admin\Api\Menu\MenuController;

Route::group([
    'prefix' => 'v1/menu',
    'as' => 'menu.',
    'namespace' => 'Menu',
    'middleware' => ['auth:admin']
], function () {
    Route::get('/tree', [MenuController::class, 'tree'])->name('tree');
});
