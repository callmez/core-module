<?php
/**
 * Created by PhpStorm.
 * User: aoxiang
 * Date: 2020-05-06
 * Time: 14:28
 */

namespace Modules\Core\database\seeds\Frontend;


use Illuminate\Database\Seeder;
use Modules\Core\src\Models\Frontend\Notice;

class NoticeTableSeeder extends Seeder
{
    public function run()
    {
        Notice::create([
            'title'   => 'App公测说明',
            'content' => 'App公测说明内容',
        ]);
    }
}