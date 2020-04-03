<?php

namespace Modules\Core\Seeds;

use Illuminate\Database\Seeder;
use Modules\Core\Seeds\Admin\AdminUserTableSeeder;
use Modules\Core\Seeds\Admin\AdminMenuTableSeeder;
use Modules\Core\Seeds\Admin\AdminPermissionTableSeeder;

class CoreDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminUserTableSeeder::class);
        $this->call(AdminMenuTableSeeder::class);
        $this->call(AdminPermissionTableSeeder::class);
    }
}
