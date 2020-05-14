<?php

namespace Modules\Core\Models\Frontend\Traits\Method;

use Carbon\Carbon;
use App\Models\User;

trait UserInvitationMethod
{
    /**
     * @return bool
     */
    public function isExpired()
    {
        return $this->expired_at->isPast();
    }

    /**
     * @return bool
     */
    public function isUsed()
    {
        return !empty($this->used_user_id);
    }

    /**
     * @param int|User $user
     *
     * @return $this
     */
    public function setUsed($user)
    {
        $this->used_user_id = with_user_id($user);
        $this->used_at = Carbon::now();

        return $this;
    }
}
