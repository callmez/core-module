<?php

namespace Modules\Core\Config;

use Modules\Core\Config\Traits\SettingStore;
use Illuminate\Config\Repository as ConfigRepository;

class Repository extends ConfigRepository
{
    use SettingStore;
}
