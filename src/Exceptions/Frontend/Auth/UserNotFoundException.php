<?php

namespace Modules\Core\Exceptions\Frontend\Auth;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserNotFoundException extends ModelNotFoundException
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
