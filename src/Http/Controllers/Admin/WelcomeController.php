<?php

namespace Modules\Core\Http\Controllers\Admin;

use Modules\Core\Http\Controllers\Controller;

class WelcomeController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.welcome');
    }
}
