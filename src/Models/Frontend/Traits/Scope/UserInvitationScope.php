<?php

namespace Modules\Core\Models\Frontend\Traits\Scope;

trait UserInvitationScope
{
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeNotExpired($query)
    {
        return $query->where('expired_at', '>=', Carbon::now());
    }
}
