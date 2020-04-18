<?php

namespace Modules\Core\src\Models\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Traits\HasTableName;

class UserInvitation extends Model
{
    use HasTableName;

    public $fillable = [
        'user_id',
        'used_user_id',
        'token',
        'used_at',
        'expired_at'
    ];

    /**
     * 应该转换为日期格式的属性.
     *
     * @var array
     */
    protected $dates = [
        'used_at',
        'expired_at',
    ];

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeNotExpired($query)
    {
        return $query->where('expired_at', '>=', Carbon::now());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
