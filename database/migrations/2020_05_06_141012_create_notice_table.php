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
            $table->string('status')->default(\Modules\Core\src\Models\Frontend\Notice::STATUS_SHOW)->comment('状态，show-显示，hide-隐藏');
            $table->string('title', 255)->nullable()->comment('标题');
            $table->text('content')->nullable()->comment('内容');


            $table->string('title_tw', 255)->default('')->comment('繁体');
            $table->text('content_tw')->nullable()->comment('繁体');

            $table->string('title_en', 255)->default('')->comment('英文');
            $table->text('content_en')->nullable()->comment('英文');

            $table->string('title_ko', 255)->default('')->comment('韩文');
            $table->text('content_ko')->nullable()->comment('韩文');

            $table->string('title_jp', 255)->default('')->comment('日文');
            $table->text('content_jp')->nullable()->comment('日文');
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
