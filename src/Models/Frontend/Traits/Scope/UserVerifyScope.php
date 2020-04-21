<?php

namespace Modules\Core\src\Models\Frontend\Traits\Scope;

use Carbon\Carbon;

trait UserVerifyScope
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
