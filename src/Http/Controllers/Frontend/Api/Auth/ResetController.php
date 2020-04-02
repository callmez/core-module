<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use Modules\Core\Http\Requests\Frontend\Auth\ResetPayPasswordRequest;
use Str;
use Carbon\Carbon;
use Modules\Core\Models\Auth\User;
use Modules\Core\Models\Auth\UserVerify;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Frontend\Auth\ResetEmailRequest;
use Modules\Core\Http\Requests\Frontend\Auth\ResetMobileRequest;
use Illuminate\Validation\ValidationException;

class ResetController extends Controller
{
    public function requestResetEmail(ResetEmailRequest $request)
    {
        /** @var User $user */
        $user = $request->user();

        $email = $request->email ?: $user->email;

        if ($email == $user->email && $user->isEmailVerified()) {
            ValidationException::withMessages([
                'email' => 'Current email is already verified.'
            ]);
        }

        if (empty($email)) {
            ValidationException::withMessages([
                'email' => 'Email must be set.'
            ]);
        }

        /** @var UserVerify $verify */
        $verify = UserVerify::create([
            'user_id' => $user->id,
            'key' => $email,
            'type' => UserVerify::TYPE_VERIFY_EMAIL,
            'token' => Str::uuid(),
            'expired_at' => Carbon::now()->addSeconds(config('user.verify.expires.email', 3600)),
        ]);

        $verify->makeOtherExpired();

        $user->sendEmailVerify($verify);

        return [];
    }

    public function requestResetMobile(ResetMobileRequest $request)
    {
        /** @var User $user */
        $user = $request->user();

        $mobile = $request->mobile ?: $user->mobile;

        if ($mobile == $user->mobile && $user->isMobileVerified()) {
            ValidationException::withMessages([
                'mobile' => 'Current mobile is already verified.'
            ]);
        }

        if (empty($mobile)) {
            ValidationException::withMessages([
                'mobile' => 'Mobile must be set.'
            ]);
        }

        /** @var UserVerify $verify */
        $verify = UserVerify::create([
            'user_id' => $user->id,
            'key' => $mobile,
            'type' => UserVerify::TYPE_VERIFY_MOBILE,
            'token' => random_int(100000, 999999),
            'expired_at' => Carbon::now()->addSeconds(config('user.verify.expires.mobile', 300)),
        ]);

        $verify->makeOtherExpired();

        $user->sendMobileVerify($verify);

        return [];
    }

    public function resetPassword()
    {
        
    }

    public function resetPayPassword(ResetPayPasswordRequest $request)
    {
        
    }
}
