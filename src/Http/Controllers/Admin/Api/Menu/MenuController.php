<?php

namespace Modules\Core\Http\Controllers\Admin\Api\Menu;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Services\Admin\AdminMenuService;

class MenuController extends Controller
{
    public function tree(Request $request, AdminMenuService $adminMenuService)
    {
        return $adminMenuService->getMenuTree();
    }
}
