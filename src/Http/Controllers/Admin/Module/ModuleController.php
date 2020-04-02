<?php

namespace Modules\Core\Http\Controllers\Admin\Module;

use Modules\Core\Http\Controllers\Controller;

class ModuleController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.module.index');
    }
}
