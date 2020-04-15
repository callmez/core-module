<?php

namespace Modules\Core\Models\Auth\Traits\Relationship;

use Modules\Core\Models\Auth\UserVerify;
use Modules\Core\Models\Auth\UserPasswordHistory;

/**
 * Class UserRelationship.
 */
trait UserRelationship
{

    /**
     * @return mixed
     */
    public function passwordHistories()
    {
        return $this->hasMany(UserPasswordHistory::class);
    }

    /**
     * @return mixed
     */
    public function verifies()
    {
        return $this->hasMany(UserVerify::class);
    }
}
