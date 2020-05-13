<?php

namespace Modules\Core\Services\Frontend;

use Modules\Core\Models\ListData;
use Modules\Core\Services\Traits\HasListData;

class ConfigService
{
    use HasListData;

    /**
     * @var ListData
     */
    protected $model;

    /**
     * @var string
     */
    protected $type = 'config';

    public function __construct(ListData $model)
    {
        $this->model = $model;
    }
}
