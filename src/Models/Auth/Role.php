<?php

namespace Modules\Core\Models\Auth;

use Modules\Core\Models\Auth\Traits\Method\RoleMethod;
use Modules\Core\Models\Traits\TableName;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Class Role.
 */
class Role extends SpatieRole
{
    use TableName,
        RoleMethod;

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? $this->getRouteKeyName(), $value)
            ->where('guard_name', $this->getDefaultGuardName())
            ->first();
    }
}
