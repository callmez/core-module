<?php

namespace Modules\Core\src\Models\Frontend\Traits\Relationship;

use App\Models\User;
use Modules\Core\src\Models\Frontend\UserInvitationTree;

trait UseInvitationRelationship
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tree()
    {
        return $this->hasOne(UserInvitationTree::class, 'user_id', 'used_user_id');
    }
}
