<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Frontend\Auth\ChangePasswordRequest;
use Modules\Core\Http\Requests\Frontend\Auth\ResetEmailRequest;
use Modules\Core\Http\Requests\Frontend\Auth\ResetMobileRequest;
use Modules\Core\Services\Frontend\UserVerifyService;
use Modules\Core\Http\Requests\Frontend\Auth\ResetPayPasswordRequest;

class ResetController extends Controller
{
    /**
     * @param ResetEmailRequest $request
     * @param UserVerifyService $userVerifyService
     *
     * @return array
     */
    public function requestResetEmail(ResetEmailRequest $request, UserVerifyService $userVerifyService)
    {
        $userVerifyService->resetEmailNotification($request->user(), $request->email);

        return [];
    }

    /**
     * @param ResetMobileRequest $request
     * @param UserVerifyService $userVerifyService
     *
     * @return array
     */
    public function requestResetMobile(ResetMobileRequest $request, UserVerifyService $userVerifyService)
    {
        $userVerifyService->resetMobileNotification($request->user(), $request->mobile);

        return [];
    }

    /**
     * @param ResetMobileRequest $request
     * @param UserVerifyService $userVerifyService
     * @return array
     */
    public function requestResetPasswordSms(ResetMobileRequest $request, UserVerifyService $userVerifyService)
    {
        $userVerifyService->resetPasswordNotification($request->user(), $request->mobile);

        return [];
    }

    /**
     * @param ChangePasswordRequest $request
     * @param UserVerifyService $userVerifyService
     * @throws \Modules\Core\Exceptions\ModelSaveException
     */
    public function resetPassword(ChangePasswordRequest $request, UserVerifyService $userVerifyService)
    {
        $userVerifyService->resetPassword($request->sms, $request->mobile, $request->password, []);
    }

    public function resetPayPassword(ResetPayPasswordRequest $request)
    {

    }
}
