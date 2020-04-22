<?php

namespace Modules\Core\src\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Traits\DynamicRelationship;
use Modules\Core\src\Models\Frontend\Traits\Method\UserInvitationMethod;
use Modules\Core\src\Models\Frontend\Traits\Scope\UserInvitationScope;
use Modules\Core\src\Models\Frontend\Traits\Relationship\UseInvitationRelationship;

class UserInvitation extends Model
{
    use HasTableName,
        DynamicRelationship;

    use UserInvitationScope,
        UserInvitationMethod,
        UseInvitationRelationship;

    /**
     * @var array
     */
    public $fillable = [
        'user_id',
        'used_user_id',
        'token',
        'used_at',
        'expired_at'
    ];

    /**
     * 应该转换为日期格式的属性.
     *
     * @var array
     */
    protected $dates = [
        'used_at',
        'expired_at',
    ];
}
