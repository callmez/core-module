<?php

namespace Modules\Core\Config;

use Modules\Core\Config\Traits\ConfigStore;
use Illuminate\Config\Repository as ConfigRepository;

class Repository extends ConfigRepository
{
    use ConfigStore;
}
