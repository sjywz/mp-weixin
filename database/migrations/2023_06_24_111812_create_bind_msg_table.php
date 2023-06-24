<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBindMsgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bind_msg', function (Blueprint $table) {
            $table->increments('id');
            $table->string('appid')->default('');
            $table->string('openid')->default('');
            $table->string('source_id')->default('');
            $table->integer('reply_id')->default('0');
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
        Schema::dropIfExists('bind_msg');
    }
}
