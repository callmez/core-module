<?php

namespace Modules\Core\Services\Frontend;

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
