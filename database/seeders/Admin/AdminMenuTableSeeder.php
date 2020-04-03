<?php

use Illuminate\Database\Seeder;
use App\Models\Admin\AdminMenu;

class AdminMenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = AdminMenu::create([
            'title' => '用户管理',
            'icon' => 'layui-icon-user',
            'url' => '',
            'is_show' => 1,
        ]);

        $system = AdminMenu::create([
            'title' => '系统管理',
            'icon' => 'icon-set',
            'url' => '',
            'is_show' => 1,
        ]);

        $role = AdminMenu::create([
            'title' => '角色权限',
            'parent_id' => $system->id,
            'url' => route('admin.auth.roles', [], false),
            'is_show' => 1,
        ]);

        $module = AdminMenu::create([
            'title' => '模块管理',
            'parent_id' => $system->id,
            'url' => route('admin.module.modules', [], false),
            'is_show' => 1,
        ]);

//        $queue = AdminMenu::create([
//            'title' => '队列监控',
//            'parent_id' => $system->id,
//            'url' => route('horizon.index', ['view' => 'dashboard'], false),
//            'is_show' => 1,
//        ]);
    }
}
