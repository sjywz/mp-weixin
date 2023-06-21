<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoReplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_reply', function (Blueprint $table) {
            $table->increments('id');
            $table->string('appid')->nullable()->comment('AppId');
            $table->tinyInteger('type')->default(0)->comment('类型');
            $table->string('key')->nullable()->comment('关键词');
            $table->string('event')->nullable()->comment('事件');
            $table->longText('context')->nullable()->comment('回复内容');
            $table->tinyInteger('wight')->default(1)->comment('权重');
            $table->tinyInteger('status')->default(1)->comment('状态');
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
        Schema::dropIfExists('auto_reply');
    }
}
