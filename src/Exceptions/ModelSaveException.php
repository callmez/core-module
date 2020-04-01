<?php

namespace Modules\Core\Exceptions;

use Exception;

class ModelSaveException extends Exception
{
    public static function withModel($modelOrClassName)
    {
        $className = is_a($modelOrClassName) ? get_class($modelOrClassName) : $modelOrClassName;
        $message = 'Model ' . $className . ' save failed';
        return new static($message);
    }
}
