<?php

namespace Modules\Core\Exceptions\Frontend\Auth;


use Exception;
use App\Models\User;

class UserVerifyException extends Exception
{
    /**
     * @var User
     */
    public $model;

    public static function withModel(User $model, $filed)
    {
        return new static('User ' . $filed . ': ' . $model->$filed . ' must be verify');
    }
}
