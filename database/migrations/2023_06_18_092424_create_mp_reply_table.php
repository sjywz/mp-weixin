<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpReplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_reply', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('type')->comment('消息类型');
            $table->longText('content')->comment('消息内容');
            $table->tinyInteger('status')->default(1)->comment('状态');
            $table->tinyInteger('wight')->default(1)->comment('权重');
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
        Schema::dropIfExists('mp_reply');
    }
}
