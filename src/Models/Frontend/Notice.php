<?php
/**
 * Created by PhpStorm.
 * User: aoxiang
 * Date: 2020-05-06
 * Time: 14:11
 */

namespace Modules\Core\src\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Frontend\Traits\Attribute\LabelAttribute;
use Modules\Core\Models\Frontend\Traits\Attribute\NoticeAttribute;
use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Traits\DynamicRelationship;

class Notice extends Model
{
    protected $table = 'system_notice';
    use HasTableName,
        DynamicRelationship;

    use NoticeAttribute;
    /**
     * @var array
     */
    public $fillable = [
        'title',
        'content',
        'status',
        'title_tw',
        'title_en',
        'title_ko',
        'content_tw',
        'content_en',
        'content_ko',
    ];

    /**
     * 显示文章
     */
    const STATUS_SHOW = 'show';

    /**
     * 隐藏文章
     */
    const STATUS_HIDE = 'hide';

}