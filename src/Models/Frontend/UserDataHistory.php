<?php

namespace Modules\Core\Models\Frontend;

use Modules\Core\Models\Traits\HasTableName;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PasswordHistory.
 */
class UserDataHistory extends Model
{
    use HasTableName;

    /**
     * 密码类型
     */
    const TYPE_PASSWORD = 'password';
    /**
     * 支付密码类型
     */
    const TYPE_PAY_PASSWORD = 'pay_password';
    /**
     * 邮箱类型
     */
    const TYPE_EMAIL = 'email';
    /**
     * 邮箱类型
     */
    const TYPE_MOBILE = 'mobile';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_data_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['data', 'type'];
}
