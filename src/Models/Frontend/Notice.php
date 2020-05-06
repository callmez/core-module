<?php
/**
 * Created by PhpStorm.
 * User: aoxiang
 * Date: 2020-05-06
 * Time: 14:11
 */

namespace Modules\Core\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Traits\DynamicRelationship;

class Notice extends Model
{
    use HasTableName,
        DynamicRelationship;
    /**
     * 显示文章
     */
    const STATUS_ENABLE = 1;

    /**
     * 隐藏文章
     */
    const STATUS_DISABLE = 0;

    /**
     * @var array
     */
    public $fillable = [
        'title',
        'content',
        'status',
    ];

    protected $table = 'system_notice';


}