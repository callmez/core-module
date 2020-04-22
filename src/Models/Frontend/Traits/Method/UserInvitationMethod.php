<?php

namespace Modules\Core\src\Models\Frontend\Traits\Method;

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

    public function setUsed(User $user)
    {
        $this->used_user_id = $user->id;
        $this->used_at = Carbon::now();

        return $this;
    }
}
