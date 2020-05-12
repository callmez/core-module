<?php

namespace Modules\Core\Services\Frontend;

use Cache;
use App\Models\User;
use Modules\Core\Services\Traits\HasQuery;
use Modules\Core\Exceptions\Frontend\Auth\UserNotFoundException;
use Modules\Core\Exceptions\Frontend\Auth\UserPasswordCheckException;
use Modules\Core\Exceptions\Frontend\Auth\UserPayPasswordCheckException;

class UserService
{
    use HasQuery {
        one as queryOne;
        getById as queryGetById;
    }

    /**
     * @var User
     */
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return mixed
     */
    public function one($where = null, array $options = [])
    {
        return $this->queryOne($where, array_merge([
            'exception' => function () {
                return new UserNotFoundException(trans('用户数据未找到'));
            }
        ], $options));
    }

    /**
     * @param $id
     *
     * @return User
     */
    public function getById($id, array $options = [])
    {
        $key = 'user:' . $id;

        return Cache::tags([$key])
            ->rememberForever($key, function () use ($id, $options) {
                return $this->queryGetById($id, $options);
            });
    }

    /**
     * @param $string
     * @param array $options
     *
     * @return array
     */
    public function getByGuessString($string, array $options = [])
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
            'isEmail' => $isEmail,
            'isMobile' => $isMobile,
            'user' => $this->one($where, $options),
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

        if (!$user || !$user->checkPassword($payPassword)) {
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

        if (!$user || !$user->checkPayPassword($payPassword)) {
            if ($options['exception'] ?? true) {
                throw new UserPayPasswordCheckException();
            }

            return false;
        }

        return true;
    }

    /**
     * @param $user
     * @param $password
     * @param array $options
     */
    public function changePassword($user, $password, array $options = [])
    {
        $user = with_user($user);

    }
}
