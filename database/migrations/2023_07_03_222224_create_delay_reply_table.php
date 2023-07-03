<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDelayReplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delay_reply', function (Blueprint $table) {
            $table->increments('id');
            $table->string('openid')->default('');
            $table->string('appid')->default('');
            $table->string('plat_appid')->default('');
            $table->integer('msg_id');
            $table->tinyInteger('status')->default('0');
            $table->dateTime('send_time');
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
        Schema::dropIfExists('delay_reply');
    }
}
