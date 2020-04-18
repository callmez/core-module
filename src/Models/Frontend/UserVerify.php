<?php

namespace Modules\Core\Models\Frontend;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserVerify extends Model
{
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

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeNotExpired($query)
    {
        return $query->where('expired_at', '>=', Carbon::now());
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     *  make same type expired
     */
    public function makeOtherExpired($delete = true)
    {
        $query = static::where('user_id', $this->user_id)
            ->where('type', $this->type)
            ->where('id', '<>', $this->id);

        if ($delete) {
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
