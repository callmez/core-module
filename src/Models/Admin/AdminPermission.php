<?php

namespace Modules\Core\Models\Auth;

use Spatie\Permission\Models\Permission;
use Modules\Core\Models\Traits\TableName;
use Modules\Core\Models\Admin\Traits\Method\RolePermission;
use Modules\Core\Models\Admin\Traits\Method\PermissionMethod;

class AdminPermission extends Permission
{
    use TableName,
        RolePermission,
        PermissionMethod;

}
