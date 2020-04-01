<?php

namespace Modules\Core\Services\Frontend;

use Cache;
use Modules\Core\Models\Auth\User;
use Modules\Core\Exceptions\Frontend\Auth\UserPayPasswordCheckException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserService
{

    /**
     * @param $id
     *
     * @return User
     */
    public function getUserById($id, array $options = [])
    {
        $key = 'user_' . $id;
        return Cache::tags(['user_' . $id])
            ->rememberForever($key, function() use ($id, $options) {
                $user = User::first(['id' => $id]);

                if (!$user && $options['exception'] ?? false) {
                    throw new ModelNotFoundException();
                }

                return $user;
            });
    }


    /**
     * @param $userId
     * @param $payPassword
     * @param array $options
     *
     * @return bool
     * @throws UserPayPasswordCheckException
     */
    public function checkPassword($userId, $payPassword, array $options = [])
    {
        $user = with_user($userId);

        if (!$user || !$user->checkPayPassword($payPassword)) {
            if ($options['exception'] ?? true) {
                throw new UserPayPasswordCheckException();
            }

            return false;
        }

        return true;
    }

    /**
     * @param $userId
     * @param $payPassword
     * @param array $options
     *
     * @return bool
     * @throws UserPayPasswordCheckException
     */
    public function checkPayPassword($userId, $payPassword, array $options = [])
    {
        $user = with_user($userId);

        if (!$user || !$user->checkPayPassword($payPassword)) {
            if ($options['exception'] ?? true) {
                throw new UserPayPasswordCheckException();
            }

            return false;
        }

        return true;
    }
}
