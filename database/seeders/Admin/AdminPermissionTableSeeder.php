<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminPermissionTableSeeder extends Seeder
{
    use DisableForeignKeys;

    public function run()
    {
        $this->disableForeignKeys();

        // Create Roles
        Role::create([
            'name' => 'admin',
            'guard_name' => 'admin',
            'title' => '管理员'
        ]);

        // Create Permissions
        Permission::create([
            'name' => 'view admin',
            'guard_name' => 'admin',
            'title' => '后台访问'
        ]);

        // Assign Permissions to other Roles
        // Note: Admin (User 1) Has all permissions via a gate in the AuthServiceProvider
        // $user->givePermissionTo('view admin');

        $this->enableForeignKeys();
    }
}
