<?php

namespace Modules\Core\Models\Admin;

use Modules\Core\Models\Auth\AdminPermission;
use Modules\Core\Models\Traits\TableName;
use Modules\Core\Models\Admin\Traits\Attribute\UserAttribute;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Plank\Mediable\Mediable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class AdminUser extends Authenticatable
{
    use TableName,
        UserAttribute,
        HasRoles,
        HasApiTokens,
        Notifiable,
        Mediable;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return AdminRole
     */
    public function getRoleClass()
    {
        if (! isset($this->roleClass)) {
            $this->roleClass = resolve(AdminRole::class);
        }

        return $this->roleClass;
    }

    /**
     * @return AdminPermission
     */
    public function getPermissionClass()
    {
        if (! isset($this->permissionClass)) {
            $this->permissionClass = resolve(AdminPermission::class);
        }

        return $this->permissionClass;
    }

    /**
     * @return string
     */
    protected function getDefaultGuardName(): string
    {
        return $this->getRoleClass()->guardName();
    }
}
