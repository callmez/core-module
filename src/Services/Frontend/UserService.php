<?php

namespace Modules\Core\Services\Frontend;

use Cache;
use App\Models\User;
use Modules\Core\src\Services\Traits\HasQueryOptions;
use Modules\Core\src\Exceptions\Frontend\Auth\UserNotFoundException;
use Modules\Core\Exceptions\Frontend\Auth\UserPasswordCheckException;
use Modules\Core\Exceptions\Frontend\Auth\UserPayPasswordCheckException;

class UserService
{
    use HasQueryOptions;

    /**
     * @param $where
     * @param array $options
     *
     * @return mixed
     */
    public function getUser($where, array $options = [])
    {
        $user = $this->withQueryOptions(User::where($where), $options)->first();

        if ( ! $user && ($options['exception'] ?? true)) {
            throw new UserNotFoundException('User not found');
        }

        return $user;
    }

    /**
     * @param $id
     *
     * @return User
     */
    public function getUserById($id, array $options = [])
    {
        $key = 'user_' . $id;

        return Cache::tags([$key])
            ->rememberForever($key, function () use ($id, $options) {

                $user = $this->getUser(['id' => $id], ['exception' => false]);

                if ( ! $user && ($options['exception'] ?? true)) {
                    throw UserNotFoundException::withId($id);
                }

                return $user;
            });
    }

    /**
     * @param $string
     * @param array $options
     *
     * @return array
     */
    public function getUserByGuessString($string, array $options = [])
    {
        $where = [];
        $isEmail = $isMobile = false;

        if (filter_var($string, FILTER_VALIDATE_EMAIL)) {
            $isEmail = true;
            $where['email'] = $string;
        } elseif (preg_match('/^[1]([3-9])[0-9]{9}$/', $string)) {
            $isMobile = true;
            $where['mobile'] = $string;
        } else {
            $where['username'] = $string;
        }

        return [
            'isEmail'  => $isEmail,
            'isMobile' => $isMobile,
            'user'     => $this->getUser($where, $options),
        ];
    }


    /**
     * @param $userId
     * @param $payPassword
     * @param array $options
     *
     * @return bool
     * @throws UserPasswordCheckException
     */
    public function checkPassword($userId, $payPassword, array $options = [])
    {
        $user = with_user($userId);

        if ( ! $user || ! $user->checkPassword($payPassword)) {
            if ($options['exception'] ?? true) {
                throw new UserPasswordCheckException('User auth failed.');
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

        if ( ! $user || ! $user->checkPayPassword($payPassword)) {
            if ($options['exception'] ?? true) {
                throw new UserPayPasswordCheckException();
            }

            return false;
        }

        return true;
    }
}
