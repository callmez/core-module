<?php

namespace Modules\Core\Services\Frontend;

use Cache;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Modules\Core\Events\Frontend\Auth\UserLoggedIn;
use Modules\Core\Events\Frontend\Auth\UserRegistered;
use Modules\Core\src\Services\Traits\HasQueryOptions;
use Modules\Core\Exceptions\Frontend\Auth\UserVerifyException;
use Modules\Core\src\Exceptions\Frontend\Auth\UserNotFoundException;
use Modules\Core\Exceptions\Frontend\Auth\UserPayPasswordCheckException;

class UserRegisterService
{

    /**
     * 用户注册
     *
     * @param array $data
     * @param array $options
     *
     * @return User
     */
    public function register(array $data, array $options = [])
    {
        /** @var User $user */
        $user = User::create([
            'username' => $data['username'],
            'password' => $data['password'],
            'mobile' => $data['mobile'] ?? '',
            'email' => $data['email'] ?? ''
        ]);

        event(new UserRegistered($user));

        $user->refresh();

        return $user;
    }
}
