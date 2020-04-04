<?php
use App\Models\User;
use App\Models\AdminUser;
use Modules\Core\Models\Auth\BaseUser;
use Modules\Core\Services\Frontend\UserService;

if (! function_exists('with_admin_user')) {
    /**
     * @param $userIdOrUser
     *
     * @return mixed
     */
    function with_admin_user($userIdOrUser)
    {
        if ($userIdOrUser instanceof AdminUser) {
            return $userIdOrUser;
        }

        return AdminUser::first($userIdOrUser);
    }
}

if (! function_exists('with_user')) {
    /**
     * @param $userIdOrUser
     *
     * @return BaseUser
     */
    function with_user($userIdOrUser)
    {
        if ($userIdOrUser instanceof User) {
            return $userIdOrUser;
        }

        return app(UserService::class)->getUserById($userIdOrUser);
    }
}


if (! function_exists('with_user_id')) {
    /**
     * @param $userIdOrUser
     *
     * @return int
     * @throws InvalidArgumentException
     */
    function with_user_id($userIdOrUser)
    {
        if (is_numeric($userIdOrUser)) {
            return $userIdOrUser;
        } elseif ($userIdOrUser instanceof User) {
            return $userIdOrUser->id;
        }

        throw new InvalidArgumentException('The argument must be instance of User or user id.');
    }
}
