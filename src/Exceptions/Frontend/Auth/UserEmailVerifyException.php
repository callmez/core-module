<?php

namespace Modules\Core\Exceptions\Frontend\Auth;


use Exception;
use App\Models\User;

class UserEmailVerifyException extends Exception
{
    /**
     * @var User
     */
    public $model;

    public static function withModel(User $model)
    {
        return new static('User email: ' . $model->email . ' must be verify');
    }
}
