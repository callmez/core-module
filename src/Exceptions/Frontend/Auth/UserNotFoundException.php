<?php

namespace Modules\Core\src\Exceptions\Frontend\Auth;

use Exception;

class UserNotFoundException extends Exception
{
    /**
     * @param $id
     *
     * @return UserNotFoundException
     */
    public static function withId($id)
    {
        return new static('User id: ' . $id . ' not found.');
    }
}
