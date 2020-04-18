<?php

namespace Modules\Core\Http\Controllers\Frontend\Auth;

use Modules\Core\Models\Frontend\UserVerify;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Events\Frontend\Auth\UserEmailVerified;
use Illuminate\Http\Request;

class EmailVerifyController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->token;
        abort_unless($token, 404);

        /** @var UserVerify $verify */
        $verify = UserVerify::where('token', $token)
            ->where('type', UserVerify::TYPE_VERIFY_EMAIL)
            ->notExpired()
            ->with(['user'])
            ->firstOrFail();

        $verify->setExpired()
            ->save();

        $verify->user
            ->setEmailVerified($verify->key)
            ->save();

        event(new UserEmailVerified($verify->user));

        return '恭喜, 您已经成功验证邮箱!';
    }
}
