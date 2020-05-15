<?php


namespace Modules\Core\Services\Admin;


use Modules\Core\Models\Config;
use Modules\Core\Services\Traits\HasListConfig;
use Modules\Core\Services\Traits\HasQuery;

class ConfigService
{
    use HasQuery;

    public $key = 'config';

    public function __construct(Config $model)
    {
        $this->model = $model;
    }


    public function update(string $module, string $key, string $value)
    {
        $config = $this->one([
            'module' => $module,
            'key' => $this->key
        ]);
        $config->setValue($key, $value);
        return $config->save();
    }
}