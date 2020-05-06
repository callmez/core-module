<?php
/**
 * Created by PhpStorm.
 * User: aoxiang
 * Date: 2020-05-06
 * Time: 11:12
 */

namespace Modules\Core\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Traits\HasTableName;
use Modules\Core\Models\Traits\DynamicRelationship;

class Label extends Model
{
    use HasTableName,
        DynamicRelationship;

    protected $table = 'label_info';

    /**
     * @var array
     */
    public $fillable = [
        'label',
        'info',
        'remark',
    ];

}