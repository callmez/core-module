<?php

namespace Modules\Core\Models\Frontend\Traits\Relationship;

use Modules\Core\Models\Frontend\UserVerify;
use Modules\Core\src\Models\Auth\UserInvitation;
use Modules\Core\Models\Frontend\UserPasswordHistory;

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

    /**
     * @return mixed
     */
    public function invitations()
    {
        return $this->hasMany(UserInvitation::class);
    }
}
