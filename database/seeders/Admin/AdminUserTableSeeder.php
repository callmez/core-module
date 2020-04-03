<?php

use Illuminate\Database\Seeder;
use Modules\Core\Models\Admin\AdminUser;

class AdminUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = AdminUser::create([
            'username' => 'admin',
            'password' => 'admin',
            'active' => 1,
        ]);

        $admin->assignRole('admin');
    }
}
