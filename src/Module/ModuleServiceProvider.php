<?php

namespace Modules\Core\Module;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Module\Traits\HasSeeds;
use Modules\Core\src\Module\Traits\HasModule;

class ModuleServiceProvider extends ServiceProvider
{
    use HasSeeds,
        HasModule;
}
