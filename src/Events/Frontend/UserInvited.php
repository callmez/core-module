<?php

namespace Modules\Core\src\Events\Frontend;

use Illuminate\Queue\SerializesModels;
use Modules\Core\src\Models\Auth\UserInvitation;

class UserInvited
{
    use SerializesModels;

    /**
     * @var
     */
    public $invitation;

    /**
     * @param $user
     */
    public function __construct(UserInvitation $invitation)
    {
        $this->invitation = $invitation;
    }
}
