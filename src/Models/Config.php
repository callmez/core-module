<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    /**
     * @var array
     */
    protected $casts = [
        'value' => 'json',
    ];

    protected $fillable = [
        'key',
        'value',
        'module',
    ];
}
