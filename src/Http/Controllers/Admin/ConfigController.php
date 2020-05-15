<?php

namespace Modules\Core\Http\Controllers\Admin;

use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\Admin\AdminMenu;

/**
 * Class DashboardController.
 */
class ConfigController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('core::admin.config', [
            'defaultPage' => url('/admin/welcome')
        ]);
    }
}
