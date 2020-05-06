<?php

namespace Modules\Core\Models\Frontend\Traits\Relationship;

use App\Models\User;
use Modules\Core\Models\Frontend\UserInvitationTree;

trait UseInvitationRelationship
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inviter()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invitee()
    {
        return $this->belongsTo(User::class, 'used_user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tree()
    {
        return $this->hasOne(UserInvitationTree::class, 'user_id', 'used_user_id');
    }
}
