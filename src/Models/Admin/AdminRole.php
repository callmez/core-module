<?php

namespace Modules\Core\Models\Admin;

use Modules\Core\Models\Admin\Traits\Method\RolePermission;
use Spatie\Permission\Models\Role;
use Modules\Core\Models\Traits\TableName;
use Modules\Core\Models\Admin\Traits\Method\RoleMethod;

class AdminRole extends Role
{
    use TableName,
        RolePermission,
        RoleMethod;


}
