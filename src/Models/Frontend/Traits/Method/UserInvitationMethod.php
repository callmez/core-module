<?php

namespace Modules\Core\src\Models\Frontend\Traits\Method;

use App\Models\User;

trait UserInvitationMethod
{
    /**
     * @return bool
     */
    public function isExpired()
    {
        return $this->expired_at;
    }

    /**
     * @return bool
     */
    public function isUsed()
    {
        return !empty($this->used_user_id);
    }

    public function setUsed(User $user)
    {

    }
}
