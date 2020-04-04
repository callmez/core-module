<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use Modules\Core\Models\Auth\BaseUser;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Events\Frontend\Auth\UserRegistered;
use Modules\Core\Http\Requests\Frontend\Auth\RegisterRequest;

/**
 * Class RegisterController.
 */
class RegisterController extends Controller
{
    /**
     * @param RegisterRequest $request
     *
     * @throws \Throwable
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(RegisterRequest $request)
    {
        abort_unless(config('access.registration'), 404);

        /** @var BaseUser $user */
        $user = BaseUser::create($request->only('username', 'password'));

        $user->refresh();

        event(new UserRegistered($user));

        return $user;
    }
}
