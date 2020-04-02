<?php
use Modules\Core\Models\Auth\User;
use Modules\Core\Models\Admin\AdminUser;
use Modules\Core\Services\Frontend\UserService;

if (! function_exists('include_files_in_folder')) {
    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function include_files_in_folder($folder)
    {
        $rdi = new RecursiveDirectoryIterator($folder);
        $it = new RecursiveIteratorIterator($rdi);

        while ($it->valid()) {
            if (! $it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                require $it->key();
            }

            $it->next();
        }
    }
}

if (! function_exists('include_route_files')) {
    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function include_route_files($folder)
    {
        include_files_in_folder($folder);
    }
}


if (! function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (! function_exists('in_admin')) {
    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function in_admin()
    {
        return auth()->getDefaultDriver() == 'admin';
    }
}

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
     * @return User
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
