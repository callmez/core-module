<?php


namespace Modules\Core\Models\Admin\Traits\Method;


trait ConfigMethod
{

    public function setValue($key, $value)
    {
        $attribute = $this->value;
        $attribute[$key] = $value;
        $this->value = $attribute;

//        $this->value[$key] = $value;
        //Indirect modification of overloaded property Modules\\Core\\Models\\Config::$value has no effect
    }
}