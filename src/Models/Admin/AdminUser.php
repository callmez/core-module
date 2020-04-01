<?php

namespace Modules\Core\Models\Admin;

use Modules\Core\Models\Traits\TableName;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Plank\Mediable\Mediable;
use Spatie\Permission\Traits\HasRoles;

class AdminUser extends Authenticatable
{
    use TableName,
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

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * @return mixed
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    public function canChangePassword()
    {
        return true;
    }


}
