<?php

namespace Modules\Core\Events\Frontend\Auth;

use Modules\Core\Models\Frontend\BaseUser;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserLoggedIn.
 */
class UserLoggedIn
{
    use SerializesModels;

    /**
     * @var
     */
    public $user;

    /**
     * @param $user
     */
    public function __construct(BaseUser $user)
    {
        $this->user = $user;
    }
}
