<?php

namespace Modules\Core\Models\Frontend;

use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Frontend\Traits\Method\PermissionMethod;
use Spatie\Permission\Models\Permission as BasePermission;

/**
 * Class Permission.
 */
class Permission extends BasePermission
{
    use HasTableName,
        PermissionMethod;
}
