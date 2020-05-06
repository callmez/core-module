<?php
/**
 * Created by PhpStorm.
 * User: aoxiang
 * Date: 2020-05-06
 * Time: 14:34
 */

namespace Modules\Core\src\Services\Frontend;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Core\Services\Traits\HasQuery;
use Modules\Core\Models\Frontend\Notice;

class NoticeService
{
    use HasQuery {
        one as queryOne;
        getById as queryGetById;
    }

    /**
     * @var Notice
     */
    protected $model;

    public function __construct(Notice $model)
    {
        $this->model = $model;
    }



}