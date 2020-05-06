<?php

namespace Modules\Core\Models\Frontend\Traits\Method;

use Carbon\Carbon;

trait UserVerifyMethod
{
    /**
     *  make same type expired
     */
    public function makeOtherExpired($delete = true)
    {
        $query = static::where('user_id', $this->user_id)
                       ->where('type', $this->type)
                       ->where('id', '<>', $this->id);

        if ($delete) {
            return $query->delete();
        }

        return $query
            ->notExpired()
            ->update([
                'expired_at' => Carbon::now(),
            ]);
    }

    /**
     *  make same type expired
     */
    public function setExpired()
    {
        $this->expired_at = Carbon::now();

        return $this;
    }
}
