<?php

namespace Modules\Core\Models\Auth;

use Modules\Core\Models\Traits\TableName;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PasswordHistory.
 */
class UserPasswordHistory extends Model
{
    use TableName;

    /**
     * 密码类型
     */
    const TYPE_PASSWORD = 'password';
    /**
     * 支付密码类型
     */
    const TYPE_PAY_PASSWORD = 'pay_password';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_password_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['password', 'type'];
}
