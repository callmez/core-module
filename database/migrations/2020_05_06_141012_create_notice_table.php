<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_notice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status')->default(\Modules\Core\src\Models\Frontend\Notice::STATUS_ENABLE)->comment('状态，1-显示，0-隐藏');
            $table->string('title', 255)->nullable()->comment('标题');
            $table->text('content')->nullable()->comment('内容');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `" . config('database.connections.mysql.prefix') . "system_notice` comment'系统通知公告'"); // 表注释

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_notice');
    }
}
