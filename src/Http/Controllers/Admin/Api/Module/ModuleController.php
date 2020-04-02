<?php

namespace Modules\Core\Http\Controllers\Admin\Api\Module;

use Modules\Core\Http\Controllers\Controller;
use Nwidart\Modules\Facades\Module;

class ModuleController extends Controller
{

    public function index()
    {
        $modules = Module::toCollection()
            ->map(function($module) {
                return [
                    'name' => $module->getName(),
                    'alias' => $module->getAlias(),
                    'description' => $module->getDescription(),
                    'keywords' => $module->get('keywords'),
                    'enabled' => $module->isEnabled(),
                    'can_disable' => true
                ];
            })->toArray();
        return array_values($modules);
    }

    public function enable($module)
    {
        Module::enable($module);
        return [];
    }

    public function disable($module)
    {
        Module::disable($module);
        return [];
    }


}
