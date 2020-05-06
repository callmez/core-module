<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('label_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('label', 50)->nullable()->comment('标签');
            $table->text('info')->comment('标签内容');
            $table->string('remark', 200)->nullable()->comment("标签说明");
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `".config('database.connections.mysql.prefix')."label_info` comment'APP显示内容标签'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('label_info');
    }
}
