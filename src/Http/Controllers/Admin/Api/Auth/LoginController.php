<?php

namespace Modules\Core\Http\Controllers\Admin\Api\Auth;

use Hash;
use Modules\Core\Models\Admin\AdminUser;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Events\Admin\Auth\UserLoggedIn;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\ThrottlesLogins;


class LoginController extends Controller
{
    use ThrottlesLogins;

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {
        $key = $this->username();

        $request->validate([
            $key => 'required|string',
            'password' => 'required',
        ]);

        if ($this->hasTooManyLoginAttempts($request)) {

            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse();
        }

        /** @var AdminUser $user */
        $user = AdminUser::where('username', $request->$key)->first();

        if (! $user || ! Hash::check($request->password, $user->getAuthPassword())) {

            $this->incrementLoginAttempts($request);

            throw ValidationException::withMessages([
                'username' => [trans('auth.failed')],
            ]);
        }

        $this->clearLoginAttempts($request);

        event(new UserLoggedIn($user));

        return [
            'access_token' => $user->createToken('admin')->plainTextToken
        ];
    }




}
