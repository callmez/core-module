<?php

namespace Modules\Core\Module;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Module\Traits\HasSeeds;

class ModuleServiceProvider extends ServiceProvider
{
    use HasSeeds;
}
