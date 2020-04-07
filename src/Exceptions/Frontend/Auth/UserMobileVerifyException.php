<?php

namespace Modules\Core\Exceptions\Frontend\Auth;

use Exception;
use App\Models\User;

class UserMobileVerifyException extends Exception
{
    /**
     * @var User
     */
    public $model;

    public static function withModel(User $model)
    {
        return new static('User mobile: ' . $model->mobile . ' must be verify');
    }
}

