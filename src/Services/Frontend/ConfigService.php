<?php


namespace Modules\Core\Services\Frontend;


use Modules\Core\Services\Traits\HasListConfig;

class ConfigService
{
    use HasListConfig;

    /**
     * @var string
     */
    protected $key = 'core::config';
}