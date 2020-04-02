<?php

namespace Modules\Core\Http\Controllers\Admin;

use Modules\Core\Http\Controllers\Controller;

class MediaController extends Controller
{
    public function index()
    {
        return view('admin.media');
    }
}
