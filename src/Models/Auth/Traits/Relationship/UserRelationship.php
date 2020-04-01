<?php

namespace Modules\Core\Models\Auth\Traits\Relationship;

use Modules\Core\Models\Auth\PasswordHistory;
use Modules\Core\Models\Auth\SocialAccount;

/**
 * Class UserRelationship.
 */
trait UserRelationship
{
    /**
     * @return mixed
     */
    public function providers()
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * @return mixed
     */
    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class);
    }
}
