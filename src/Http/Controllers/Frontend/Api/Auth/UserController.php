<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use Modules\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function info(Request $request)
    {
        return $request->user();
    }
}
