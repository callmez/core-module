<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use Modules\Core\Models\Frontend\BaseUser;
use Modules\Core\Models\Frontend\UserVerify;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Events\Frontend\Auth\UserMobileVerified;
use Modules\Core\Http\Requests\Frontend\Auth\ResetMobileRequest;
use Modules\Core\Services\Frontend\UserVerifyService;

class VerifyController extends Controller
{

    public function verifyMobile(ResetMobileRequest $request, UserVerifyService $userVerifyService)
    {
        /** @var BaseUser $user */
        $user = $request->user();

        $userVerifyService->


        /** @var UserVerify $verify */
        $verify = UserVerify::where('user_id', $user->id)
            ->where('token', $request->code)
            ->where('type', UserVerify::TYPE_VERIFY_MOBILE)
            ->notExpired()
            ->with(['user'])
            ->firstOrFail();

        $verify->setExpired()
               ->save();

        $verify->user
            ->setMobileVerified($verify->key)
            ->save();

        event(new UserMobileVerified($verify->user));

        return [];
    }
}
