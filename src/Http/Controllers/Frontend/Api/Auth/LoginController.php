<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use App\Models\User;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Frontend\Auth\LoginRequest;
use Modules\Core\Events\Frontend\Auth\UserLoggedIn;
use Modules\Core\Exceptions\Frontend\Auth\UserEmailVerifyException;
use Modules\Core\Exceptions\Frontend\Auth\UserMobileVerifyException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Modules\Core\Services\Frontend\UserService;

class LoginController extends Controller
{
    use ThrottlesLogins;

    public function username()
    {
        return 'username';
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request, UserService $userService)
    {
        if ($this->hasTooManyLoginAttempts($request)) {

            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse();
        }

        /** @var User $user */
        [
            'isEmail' => $isEmail,
            'isMobile' => $isMobile,
            'user' => $user
        ] = $userService->getUserByGuessString($request->username);

        if (! $user || ! $user->checkPassword($request->password)) {

            $this->incrementLoginAttempts($request);

            throw ValidationException::withMessages([
                'username' => [trans('auth.failed')],
            ]);
        }

        if ($isEmail && !$user->isEmailVerified()) {

            throw UserEmailVerifyException::withModel($user);

        } elseif ($isMobile && !$user->isMobileverified()) {

            throw UserMobileVerifyException::withModel($user);

        }

        $this->clearLoginAttempts($request);

        event(new UserLoggedIn($user));

        return [
            'access_token' => $user->createToken($request->device ?: 'frontend')->plainTextToken
        ];
    }
}
