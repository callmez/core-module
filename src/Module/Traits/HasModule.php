<?php

namespace Modules\Core\Module\Traits;

use Nwidart\Modules\Exceptions\ModuleNotFoundException;
use UnexpectedValueException;
use Nwidart\Modules\Module;
use Nwidart\Modules\Facades\Module as ModuleManager;

trait HasModule
{
    /**
     * @var Module
     */
    private $_module;

    /**
     * @return Module
     */
    public function module(array $options = []): Module
    {
        if ($this->_module === null) {
            if ($this->moduleNameLower === null) {
                throw new UnexpectedValueException('Property "moduleNameLower" must be set.');
            }

            $this->_module = ModuleManager::find($this->moduleNameLower);

            if (!$this->_module) {
                throw new ModuleNotFoundException('Module ' . $this->moduleNameLower . ' not found');
            }
            if (($options['enabled'] ?? true) && !$this->_module->isEnabled()) {
                throw new UnexpectedValueException('Module ' . $this->moduleNameLower . ' is disabled');
            }
        }

        return $this->_module;
    }
}
