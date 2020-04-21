<?php

namespace Modules\Core\Models\Frontend;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Traits\DynamicRelationship;
use Modules\Core\src\Models\Frontend\Traits\Method\UserVerifyMethod;
use Modules\Core\src\Models\Frontend\Traits\Scope\UserVerifyScope;
use Modules\Core\src\Models\Frontend\Traits\Relationship\UserVerifyRelationship;

class UserVerify extends Model
{
    use HasTableName,
        DynamicRelationship;

    use UserVerifyScope,
        UserVerifyMethod,
        UserVerifyRelationship;

    const UPDATED_AT = null;

    public $fillable = [
        'user_id',
        'key',
        'token',
        'type',
        'expired_at',
    ];

    /**
     * 应该转换为日期格式的属性.
     *
     * @var array
     */
    protected $dates = [
        'expired_at',
    ];

}
