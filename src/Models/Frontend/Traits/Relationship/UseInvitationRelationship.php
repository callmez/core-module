<?php

namespace Modules\Core\src\Models\Frontend\Traits\Relationship;

use App\Models\User;

trait UseInvitationRelationship
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
