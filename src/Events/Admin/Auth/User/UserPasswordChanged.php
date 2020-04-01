<?php

namespace Modules\Core\Events\Admin\Auth\User;

use Illuminate\Queue\SerializesModels;

/**
 * Class UserPasswordChanged.
 */
class UserPasswordChanged
{
    use SerializesModels;

    /**
     * @var
     */
    public $user;

    /**
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
