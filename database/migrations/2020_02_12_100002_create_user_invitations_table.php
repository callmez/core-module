<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_invitations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->default(0)->comment('关联用户ID');
            $table->bigInteger('used_user_id')->nullable()->default(0)->comment('使用的用户ID');
            $table->string('token', 40)->unique()->nullable()->default('')->comment('邀请码');
            $table->dateTime('used_at')->nullable()->comment('使用时间');
            $table->dateTime('expired_at')->nullable()->comment('过期时间');
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
        Schema::dropIfExists('user_invitations');
    }
}
