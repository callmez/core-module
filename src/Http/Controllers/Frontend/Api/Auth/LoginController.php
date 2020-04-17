<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Frontend\Auth\LoginRequest;
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
     * @param LoginRequest $request
     * @param UserService $userService
     *
     * @return array|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function loginByGuessString(LoginRequest $request, UserService $userService)
    {
        if ($this->hasTooManyLoginAttempts($request)) {

            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse();
        }

        try {
            $field = $this->username();
            $user = $userService->loginByGuessString($request->$field, $request->password, ['field' => $field]);

            $this->clearLoginAttempts($request);

            return [
                'access_token' => $user->createToken($request->device ?: 'frontend')->plainTextToken
            ];
        } catch(\Exception $e) {
            $this->incrementLoginAttempts($request);

            throw $e;
        }
    }
}
