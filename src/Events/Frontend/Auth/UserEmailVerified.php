<?php

namespace Modules\Core\Events\Frontend\Auth;

use Modules\Core\Models\Auth\User;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserEmailVerified.
 */
class UserEmailVerified
{
    use SerializesModels;

    /**
     * @var
     */
    public $user;

    /**
     * @param $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
