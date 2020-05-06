<?php
/**
 * Created by PhpStorm.
 * User: aoxiang
 * Date: 2020-05-06
 * Time: 11:53
 */

namespace Modules\Core\src\Http\Controllers\Frontend\Api;

use Illuminate\Http\Request;

use Modules\Core\Http\Controllers\Controller;
use Modules\Core\src\Services\Frontend\LabelService;

class LabelController extends Controller
{
    public function index(Request $request, LabelService $labelService)
    {
        return $labelService->paginate();
    }

    public function info(Request $request, LabelService $labelService)
    {
        return $labelService->getLabelInfoByLabel($request->label);
    }
}