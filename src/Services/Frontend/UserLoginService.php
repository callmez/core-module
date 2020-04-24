<?php

namespace Modules\Core\Services\Frontend;

use App\Models\User;
use Modules\Core\Events\Frontend\UserLoggedIn;
use Modules\Core\Exceptions\Frontend\Auth\UserVerifyException;

class UserLoginService
{
    /**
     * @param $string
     * @param $password
     * @param array $options
     *
     * @return User
     */
    public function loginByGuessString($string, $password, array $options = [])
    {
        /** @var UserService $userService */
        $userService = resolve(UserService::class);

        /** @var User $user */
        [
            'isEmail' => $isEmail,
            'isMobile' => $isMobile,
            'user' => $user
        ] = $userService->getByGuessString($string);

        $userService->checkPassword($user, $password);

        if ($isEmail && !$user->isEmailVerified()) {
            throw UserVerifyException::withModel($user, 'email');
        } elseif ($isMobile && !$user->isMobileverified()) {
            throw UserVerifyException::withModel($user, 'mobile');
        }

        event(new UserLoggedIn($user));

        return $user;
    }
}
