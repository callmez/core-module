<?php

namespace Modules\Core\Models\Frontend;

use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Frontend\Traits\Method\RoleMethod;
use Spatie\Permission\Models\Role as BaseRole;

/**
 * Class Role.
 */
class Role extends BaseRole
{
    use HasTableName,
        RoleMethod;


}
