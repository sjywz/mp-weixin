<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('appid')->index();
            $table->string('openid')->unique();
            $table->string('unionid')->index()->nullable();
            $table->tinyInteger('subscribe')->default('0');
            $table->integer('subscribe_time');
            $table->string('remark')->nullable();
            $table->string('groupid')->nullable();
            $table->string('tagid_list')->nullable();
            $table->string('subscribe_scene')->nullable();
            $table->string('language')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_users');
    }
}
