<?php

namespace Modules\Core\Events\Frontend\Auth;

use Modules\Core\Models\Auth\BaseUser;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserProviderRegistered.
 */
class UserProviderRegistered
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
