<?php

namespace Modules\Core\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Traits\DynamicRelationship;
use Modules\Core\Models\Admin\Traits\Scope\AdminMenuScope;

class AdminMenu extends Model
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    use HasTableName,
        DynamicRelationship;

    use AdminMenuScope;

}
