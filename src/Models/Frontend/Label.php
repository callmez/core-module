<?php
/**
 * Created by PhpStorm.
 * User: aoxiang
 * Date: 2020-05-06
 * Time: 11:12
 */

namespace Modules\Core\src\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Frontend\Traits\Attribute\LabelAttribute;
use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Traits\DynamicRelationship;

class Label extends Model
{
    protected $table = 'label_info';
    use HasTableName,
        DynamicRelationship;

    use LabelAttribute;
    /**
     * @var array
     */
    public $fillable = [
        'label',
        'info',
        'remark',
        'info_cn',
        'info_tw',
        'info_en',
        'info_ko',
        'info_jp',
    ];

}