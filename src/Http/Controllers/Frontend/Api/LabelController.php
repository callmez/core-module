<?php

namespace Modules\Core\Http\Controllers\Frontend\Api;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Services\Frontend\LabelService;

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
