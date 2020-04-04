<?php

namespace Modules\Core\Models\Auth;

use Modules\Core\Models\Traits\TableName;
use Modules\Core\Models\Auth\Traits\Method\RoleMethod;
use Spatie\Permission\Models\Role as BaseRole;

/**
 * Class Role.
 */
class Role extends BaseRole
{
    use TableName,
        RoleMethod;


}
