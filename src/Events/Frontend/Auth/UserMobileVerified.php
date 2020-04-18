<?php

namespace Modules\Core\Events\Frontend\Auth;

use Modules\Core\Models\Frontend\BaseUser;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserMobileVerified.
 */
class UserMobileVerified
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
