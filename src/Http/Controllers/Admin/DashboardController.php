<?php

namespace Modules\Core\Http\Controllers\Admin;

use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\Admin\AdminMenu;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.dashboard', [
            'menu' => AdminMenu::menu(),
            'defaultPage' => url('/admin/welcome')
        ]);
    }
}
