<?php

namespace Modules\Core\Models\Auth;

use Modules\Core\Models\Traits\TableName;
use Modules\Core\Models\Auth\Traits\Method\PermissionMethod;
use Spatie\Permission\Models\Permission as BasePermission;

/**
 * Class Permission.
 */
class Permission extends BasePermission
{
    use TableName,
        PermissionMethod;
}
