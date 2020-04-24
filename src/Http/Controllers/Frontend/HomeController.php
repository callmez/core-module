<?php

namespace Modules\Core\Http\Controllers\Frontend;

use Modules\Core\Http\Controllers\Controller;

/**
 * Class HomeController.
 */
class HomeController extends Controller
{

    public function index()
    {
        return 'hello world!';
    }
}
