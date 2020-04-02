<?php

namespace Modules\Core\Http\Controllers\Admin\Api\Auth;

use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Events\Admin\Auth\UserLoggedOut;
use Illuminate\Http\Request;


class LogoutController extends Controller
{
    /**
     * Log the user out of the application.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        event(new UserLoggedOut($user));

        $user->currentAccessToken()->delete();

        return [];
    }
}
