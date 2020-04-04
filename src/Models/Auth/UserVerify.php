<?php

namespace Modules\Core\Models\Auth;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserVerify extends Model
{
    const TYPE_VERIFY_EMAIL = 'verify_email';
    const TYPE_VERIFY_MOBILE = 'verify_mobile';

    const UPDATED_AT = null;

    public $fillable = [
        'user_id',
        'key',
        'token',
        'type',
        'expired_at',
    ];

    /**
     * 应该转换为日期格式的属性.
     *
     * @var array
     */
    protected $dates = [
        'expired_at',
    ];


    public function scopeNotExpired($query)
    {
        return $query->where('expired_at', '>=', Carbon::now());
    }

    public function user()
    {
        return $this->belongsTo(BaseUser::class, 'user_id', 'id');
    }

    /**
     *  make same type expired
     */
    public function makeOtherExpired()
    {
        $query = static::where('user_id', $this->user_id)
            ->where('type', $this->type)
            ->where('id', '<>', $this->id);

        if (config('user.verify.remove_expired', true)) {
            return $query->delete();
        }

        return $query
            ->notExpired()
            ->update([
                'expired_at' => Carbon::now(),
            ]);
    }

    /**
     *  make same type expired
     */
    public function setExpired()
    {
        $this->expired_at = Carbon::now();

        return $this;
    }
}
