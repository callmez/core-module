<?php

namespace Modules\Core\Models\Auth;

use Modules\Core\Models\Traits\Uuid;
use Modules\Core\Models\Traits\TableName;
use Modules\Core\Models\Traits\DynamicRelationship;
use Modules\Core\Models\Auth\Traits\Scope\UserScope;
use Modules\Core\Models\Auth\Traits\Method\UserMethod;
use Modules\Core\Models\Auth\Traits\Attribute\UserAttribute;
use Modules\Core\Models\Auth\Traits\Relationship\UserRelationship;
use Modules\Core\Models\Auth\Traits\Notification\UserNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Plank\Mediable\Mediable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User.
 */
abstract class BaseUser extends Authenticatable
{
    use Uuid,
        TableName,
        Mediable,
        HasRoles,
        Notifiable,
        SoftDeletes,
        HasApiTokens,
        DynamicRelationship;

    use UserScope,
        UserMethod,
        UserAttribute,
        UserRelationship,
        UserNotification;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'mobile',
        'avatar',
        'active',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'auth' => 'boolean'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'last_login_at',
        'password_changed_at',
        'pay_password_changed_at',
        'mobile_verified_at',
        'email_verified_at',
        'auth_verified_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'pay_password',
        'remember_token',
        'deleted_at'
    ];
}
