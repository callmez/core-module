<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use App\Models\User;
use Modules\Core\Http\Requests\Frontend\Auth\SetMobileRequest;
use Modules\Core\Http\Requests\Frontend\Auth\VerifyMobileRequest;
use Modules\Core\Models\Frontend\UserVerify;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Events\Frontend\UserMobileVerified;
use Modules\Core\Services\Frontend\UserVerifyService;

class VerifyController extends Controller
{

    public function verifyMobile(VerifyMobileRequest $request, UserVerifyService $userVerifyService)
    {
        $userVerifyService->setMobile($request->user(), $request->mobile, $request->code);
        return [];
    }


    /**
     * @param SetMobileRequest $request
     * @param UserVerifyService $userVerifyService
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function requestSetMobileSms(SetMobileRequest $request, UserVerifyService $userVerifyService)
    {
        $userVerifyService->setMobileNotification($request->user(), $request->mobile, []);
        return [];
    }
}
