<?php

namespace Modules\Core\Models\Admin\Traits\Method;

trait RolePermission
{
    public function __construct(array $attributes = [])
    {
        parent::__construct(array_merge(['guard_name' => 'admin'], $attributes));
    }

    public static function create(array $attributes = [])
    {
        return parent::create(array_merge(['guard_name' => 'admin'], $attributes));
    }

    public function guardName(): string
    {
        return 'admin';
    }
}
