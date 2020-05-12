<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Frontend\Auth\ChangePasswordRequest;
use Modules\Core\Http\Requests\Frontend\Auth\ResetMobileRequest;
use Modules\Core\Services\Frontend\UserVerifyService;

class ChangePasswordController extends Controller
{

    /**
     * @param ResetMobileRequest $request
     * @param UserVerifyService $userVerifyService
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function requestResetPasswordSms(ResetMobileRequest $request, UserVerifyService $userVerifyService)
    {
        $userVerifyService->changePasswordNotification($request->user(), $request->mobile);

        return [];
    }

    public function resetPassword(ResetMobileRequest $request, UserVerifyService $userVerifyService)
    {
        return $userVerifyService->resetPassword($request->sms, $request->mobile, $request->password, []);
    }
}