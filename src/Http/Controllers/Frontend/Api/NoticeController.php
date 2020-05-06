<?php
/**
 * Created by PhpStorm.
 * User: aoxiang
 * Date: 2020-05-06
 * Time: 14:34
 */

namespace Modules\Core\src\Http\Controllers\Frontend\Api;


use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\src\Services\Frontend\NoticeService;

class NoticeController extends Controller
{
    public function index(Request $request, NoticeService $noticeService)
    {
        return $noticeService->paginate();
    }

    public function info(Request $request, NoticeService $noticeService)
    {
        return $noticeService->getById($request->id);
    }
}