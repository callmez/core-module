<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->default(0)->comment('父菜单ID');
            $table->string('title')->comment('标题');
            $table->string('icon')->nullable()->comment('图标');
            $table->string('url')->comment('URL地址');
            $table->unsignedTinyInteger('is_show')->nullable()->default(0)->comment('是否显示');
            $table->unsignedSmallInteger('sort')->nullable()->default(0)->comment('排序');

            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_menus');
    }
}
